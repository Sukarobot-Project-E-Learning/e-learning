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
            return view('instructor.quizzes.index', [
                'programs' => collect([]),
            ]);
        }

        $programs = DB::table('data_programs')
            ->leftJoin('lms_assignments', function ($join) {
                $join->on('lms_assignments.program_id', '=', 'data_programs.id')
                    ->where(function ($query) {
                        $query->where('lms_assignments.type', '=', 'post-test')
                            ->orWhereNull('lms_assignments.type');
                    });
            })
            ->leftJoin('lms_submissions', 'lms_submissions.assignment_id', '=', 'lms_assignments.id')
            ->where('data_programs.instructor_id', $trainerId)
            ->select(
                'data_programs.id',
                'data_programs.program',
                'data_programs.image',
                'data_programs.slug',
                DB::raw('COUNT(DISTINCT lms_assignments.id) as final_assignment_count'),
                DB::raw('COUNT(DISTINCT lms_submissions.id) as submission_count')
            )
            ->groupBy(
                'data_programs.id',
                'data_programs.program',
                'data_programs.image',
                'data_programs.slug'
            )
            ->orderBy('data_programs.program')
            ->get();

        return view('instructor.quizzes.index', compact('programs'));
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