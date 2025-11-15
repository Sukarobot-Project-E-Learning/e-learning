<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Get quizzes for current instructor
        // $quizzes = Quiz::where('instructor_id', auth()->id())->get();
        
        // Dummy data untuk sementara
        $quizzes = [
            [
                'id' => 1,
                'title' => 'Postest Digital Marketing',
                'program' => 'Strategi Digital Marketing',
                'total_questions' => 10,
                'total_responses' => 25,
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'title' => 'Postest Workshop Branding',
                'program' => 'Workshop Branding',
                'total_questions' => 15,
                'total_responses' => 18,
                'created_at' => '2024-01-20'
            ],
        ];

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

