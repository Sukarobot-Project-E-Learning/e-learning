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
        
        // Fallback or explicit check if needed
        return null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $trainerId = $this->getTrainerId();

        if (!$trainerId) {
            // Return empty paginated collection
            $quizzes = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                5,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return view('instructor.quizzes.index', compact('quizzes'));
        }

        // Base query
        $query = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->select(
                'quizzes.*',
                'data_programs.program as program_name'
            )
            ->where('quizzes.instructor_id', $trainerId);

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quizzes.title', 'like', "%{$search}%")
                  ->orWhere('data_programs.program', 'like', "%{$search}%");
            });
        }

        // Apply Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        // Map sort columns to DB columns
        $sortMap = [
            'title' => 'quizzes.title',
            'program' => 'data_programs.program',
            'created_at' => 'quizzes.created_at',
        ];

        if (array_key_exists($sort, $sortMap)) {
            $query->orderBy($sortMap[$sort], $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('quizzes.created_at', 'desc');
        }

        // Pagination
        $perPage = $request->integer('per_page', 5);
        if ($perPage < 1 || $perPage > 100) $perPage = 5;

        $quizzes = $query->paginate($perPage)->withQueryString();

        // Transform data (append counts and formatted dates)
        $quizzes->getCollection()->transform(function($quiz) {
            // Get total questions count
            $quiz->total_questions = DB::table('quiz_questions')
                ->where('quiz_id', $quiz->id)
                ->count();

            // Get total responses count
            $quiz->total_responses = DB::table('quiz_responses')
                ->where('quiz_id', $quiz->id)
                ->count();

            $quiz->program = $quiz->program_name ?? 'N/A';
            $quiz->type = $quiz->type ?? 'Postest';
            $quiz->status = $quiz->status ?? 'draft';
            
            // Add formatted dates
            $quiz->formatted_created_at = $quiz->created_at ? date('d M Y', strtotime($quiz->created_at)) : '-';
            $quiz->time_ago = $quiz->created_at ? \Carbon\Carbon::parse($quiz->created_at)->diffForHumans() : '';

            return $quiz;
        });

        return view('instructor.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get programs for dropdown
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
                'type' => 'Postest', // Default or from input
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->questions as $q) {
                // Ensure correct answer is set for non-essay
                $correctAnswer = $q['correct_answer'] ?? null;
                if ($q['type'] === 'multiple_choice' && is_numeric($correctAnswer)) {
                    // Store the index or the value? Usually store the value if options are strings.
                    // But the UI sends index. Let's store the text value if possible, or just the index.
                    // If the UI sends index, we need to make sure we store what the frontend expects.
                    // Looking at create.blade.php: value="optIndex". So it sends 0, 1, 2...
                    // But if options change, index might be wrong. Ideally store the text.
                    // However, let's stick to what's simple. 
                    // Actually, let's store the text value of the option.
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
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$quiz) {
            return redirect()->route('instructor.quizzes.index')->with('error', 'Tugas tidak ditemukan');
        }

        $questions = DB::table('quiz_questions')->where('quiz_id', $id)->get();
        
        // Transform options
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
            
            // Reverse correct answer to index for multiple choice if possible
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

            // Replace questions (simplest approach)
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
            DB::table('quiz_responses')->where('quiz_id', $id)->delete(); // Assuming no other dependencies
            DB::table('quizzes')->where('id', $id)->delete();
            
            DB::commit();
            return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('instructor.quizzes.index')->with('error', 'Gagal menghapus tugas');
        }
    }
}