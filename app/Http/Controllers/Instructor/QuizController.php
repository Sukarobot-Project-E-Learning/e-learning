<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Get current instructor/trainer ID
     */
    private function getTrainerId()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();

            if ($trainer) {
                return $trainer->id;
            }
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     * Supports both regular page load and AJAX requests for dynamic filtering
     */
    public function index(Request $request)
    {
        $trainerId = $this->getTrainerId();

        if (!$trainerId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'data' => [],
                    'stats' => ['published' => 0, 'draft' => 0],
                    'pagination' => ['current_page' => 1, 'last_page' => 1, 'total' => 0]
                ]);
            }

            return view('instructor.quizzes.index', [
                'quizzes' => collect([])
            ]);
        }

        // Base query
        $query = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->select(
                'quizzes.*',
                'data_programs.program as program_name'
            )
            ->where('quizzes.instructor_id', $trainerId);

        // Get stats for all quizzes (before filtering)
        $allQuizzes = DB::table('quizzes')
            ->where('instructor_id', $trainerId)
            ->get();

        $stats = [
            'published' => $allQuizzes->where('status', 'published')->count(),
            'draft' => $allQuizzes->where('status', 'draft')->count(),
            'total' => $allQuizzes->count(),
        ];

        // Apply search filter if provided
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('quizzes.title', 'LIKE', $searchTerm)
                    ->orWhere('data_programs.program', 'LIKE', $searchTerm);
            });
        }

        // Apply sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = ['title', 'created_at', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }

        $query->orderBy('quizzes.' . $sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');

        // Handle AJAX request for dynamic table updates
        if ($request->ajax() || $request->wantsJson()) {
            $perPage = min((int) $request->get('per_page', 10), 100);
            $quizzes = $query->paginate($perPage);

            // Transform data
            $data = collect($quizzes->items())->map(function ($quiz) {
                $quiz->total_questions = DB::table('quiz_questions')
                    ->where('quiz_id', $quiz->id)
                    ->count();
                $quiz->total_responses = DB::table('quiz_responses')
                    ->where('quiz_id', $quiz->id)
                    ->count();
                $quiz->program = $quiz->program_name ?? 'N/A';
                $quiz->type = $quiz->type ?? 'Postest';
                $quiz->status = $quiz->status ?? 'draft';
                return $quiz;
            });

            return response()->json([
                'data' => $data,
                'stats' => $stats,
                'pagination' => [
                    'current_page' => $quizzes->currentPage(),
                    'last_page' => $quizzes->lastPage(),
                    'per_page' => $quizzes->perPage(),
                    'total' => $quizzes->total(),
                    'from' => $quizzes->firstItem(),
                    'to' => $quizzes->lastItem(),
                ]
            ]);
        }

        // Regular page load - get all data for client-side filtering
        $quizzes = $query->get();

        // Transform data
        $quizzes = $quizzes->map(function ($quiz) {
            $quiz->total_questions = DB::table('quiz_questions')
                ->where('quiz_id', $quiz->id)
                ->count();
            $quiz->total_responses = DB::table('quiz_responses')
                ->where('quiz_id', $quiz->id)
                ->count();
            $quiz->program = $quiz->program_name ?? 'N/A';
            $quiz->type = $quiz->type ?? 'Postest';
            $quiz->status = $quiz->status ?? 'draft';
            return $quiz;
        });

        return view('instructor.quizzes.index', compact('quizzes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainerId = $this->getTrainerId();
        $programs = DB::table('data_programs')
            ->where('instructor_id', $trainerId)
            ->select('id', 'program as title')
            ->get();

        return view('instructor.quizzes.create', compact('programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $trainerId = $this->getTrainerId();
        if (!$trainerId) {
            return redirect()->back()->with('error', 'Instruktur tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'program_id' => 'required|exists:data_programs,id',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,essay,true_false',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'nullable',
            'questions.*.points' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $quizId = DB::table('quizzes')->insertGetId([
                'instructor_id' => $trainerId,
                'program_id' => $request->program_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'published',
                'type' => 'Postest',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->questions as $q) {
                $correctAnswer = $q['correct_answer'] ?? null;
                if ($q['type'] === 'multiple_choice' && is_numeric($correctAnswer)) {
                    $options = $q['options'] ?? [];
                    if (isset($options[$correctAnswer])) {
                        $correctAnswer = $options[$correctAnswer];
                    }
                }

                DB::table('quiz_questions')->insert([
                    'quiz_id' => $quizId,
                    'question' => $q['text'],
                    'type' => $q['type'],
                    'options' => isset($q['options']) ? json_encode($q['options']) : null,
                    'correct_answer' => $correctAnswer,
                    'points' => $q['points'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat tugas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trainerId = $this->getTrainerId();
        $quiz = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->select('quizzes.*', 'data_programs.program as program_name')
            ->where('quizzes.id', $id)
            ->where('quizzes.instructor_id', $trainerId)
            ->first();

        if (!$quiz) {
            return redirect()->route('instructor.quizzes.index')->with('error', 'Tugas tidak ditemukan');
        }

        $questions = DB::table('quiz_questions')->where('quiz_id', $id)->get();

        foreach ($questions as $q) {
            $q->options = json_decode($q->options ?? '[]', true);
        }

        return view('instructor.quizzes.show', compact('quiz', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trainerId = $this->getTrainerId();
        $quiz = DB::table('quizzes')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$quiz) {
            return redirect()->route('instructor.quizzes.index')->with('error', 'Tugas tidak ditemukan');
        }

        $questions = DB::table('quiz_questions')->where('quiz_id', $id)->get();
        foreach ($questions as $q) {
            $q->options = json_decode($q->options ?? '[]', true);

            if ($q->type === 'multiple_choice' && $q->options && $q->correct_answer) {
                $index = array_search($q->correct_answer, $q->options);
                if ($index !== false) {
                    $q->correct_answer_index = $index;
                }
            }
        }

        $programs = DB::table('data_programs')
            ->where('instructor_id', $trainerId)
            ->select('id', 'program as title')
            ->get();

        return view('instructor.quizzes.edit', compact('quiz', 'questions', 'programs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trainerId = $this->getTrainerId();
        $quiz = DB::table('quizzes')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$quiz) {
            return redirect()->route('instructor.quizzes.index')->with('error', 'Tugas tidak ditemukan');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'program_id' => 'required|exists:data_programs,id',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,essay,true_false',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'nullable',
            'questions.*.points' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            DB::table('quizzes')->where('id', $id)->update([
                'program_id' => $request->program_id,
                'title' => $request->title,
                'description' => $request->description,
                'updated_at' => now(),
            ]);

            DB::table('quiz_questions')->where('quiz_id', $id)->delete();

            foreach ($request->questions as $q) {
                $correctAnswer = $q['correct_answer'] ?? null;
                if ($q['type'] === 'multiple_choice' && is_numeric($correctAnswer)) {
                    $options = $q['options'] ?? [];
                    if (isset($options[$correctAnswer])) {
                        $correctAnswer = $options[$correctAnswer];
                    }
                }

                DB::table('quiz_questions')->insert([
                    'quiz_id' => $id,
                    'question' => $q['text'],
                    'type' => $q['type'],
                    'options' => isset($q['options']) ? json_encode($q['options']) : null,
                    'correct_answer' => $correctAnswer,
                    'points' => $q['points'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengupdate tugas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trainerId = $this->getTrainerId();
        $quiz = DB::table('quizzes')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$quiz) {
            return redirect()->route('instructor.quizzes.index')->with('error', 'Tugas tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            DB::table('quiz_questions')->where('quiz_id', $id)->delete();
            DB::table('quiz_responses')->where('quiz_id', $id)->delete();
            DB::table('quizzes')->where('id', $id)->delete();

            DB::commit();
            return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('instructor.quizzes.index')->with('error', 'Gagal menghapus tugas');
        }
    }
}