<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get current instructor/trainer ID
        $trainerId = null;
        
        if (auth()->check()) {
            $user = auth()->user();
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();
            
            if ($trainer) {
                $trainerId = $trainer->id;
            }
        }
        
        if (!$trainerId) {
            $trainer = DB::table('data_trainers')
                ->where('status_trainer', 'Aktif')
                ->first();
            $trainerId = $trainer ? $trainer->id : null;
        }

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

        // Get quizzes for current instructor with pagination (5 per page)
        $quizzes = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->select(
                'quizzes.*',
                'data_programs.program as program_name'
            )
            ->where('quizzes.instructor_id', $trainerId)
            ->orderBy('quizzes.created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $quizzes->getCollection()->transform(function($quiz) {
            // Get total questions count
            $totalQuestions = DB::table('quiz_questions')
                ->where('quiz_id', $quiz->id)
                ->count();

            // Get total responses count
            $totalResponses = DB::table('quiz_responses')
                ->where('quiz_id', $quiz->id)
                ->count();

            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'program' => $quiz->program_name ?? 'N/A',
                'type' => $quiz->type ?? 'Postest',
                'status' => $quiz->status ?? 'draft',
                'total_questions' => $totalQuestions,
                'total_responses' => $totalResponses,
                'created_at' => $quiz->created_at ? date('Y-m-d', strtotime($quiz->created_at)) : '-'
            ];
        });

        return view('instructor.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructor.quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // $validated = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'program_id' => 'required|exists:programs,id',
        //     'description' => 'nullable|string',
        //     'questions' => 'required|array',
        //     'questions.*.question' => 'required|string',
        //     'questions.*.type' => 'required|in:multiple_choice,essay',
        //     'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
        //     'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice',
        // ]);
        
        // Store logic here
        return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // TODO: Get quiz by id with responses
        return view('instructor.quizzes.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get quiz by id
        return view('instructor.quizzes.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('instructor.quizzes.index')->with('success', 'Tugas/Postest berhasil dihapus');
    }
}

