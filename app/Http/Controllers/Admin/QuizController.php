<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programs = DB::table('data_programs')
            ->join('lms_assignments', 'lms_assignments.program_id', '=', 'data_programs.id')
            ->leftJoin('data_trainers', 'data_programs.instructor_id', '=', 'data_trainers.id')
            ->leftJoin('lms_submissions', 'lms_submissions.assignment_id', '=', 'lms_assignments.id')
            ->where(function ($query) {
                $query->where('lms_assignments.type', 'post-test')
                    ->orWhereNull('lms_assignments.type');
            })
            ->select(
                'data_programs.id',
                'data_programs.program',
                'data_programs.image',
                'data_programs.slug',
                'data_trainers.nama as instructor_name',
                DB::raw('COUNT(DISTINCT lms_assignments.id) as posttest_count'),
                DB::raw('COUNT(DISTINCT lms_submissions.id) as submission_count')
            )
            ->groupBy(
                'data_programs.id',
                'data_programs.program',
                'data_programs.image',
                'data_programs.slug',
                'data_trainers.nama'
            )
            ->orderBy('data_programs.program')
            ->get();

        return view('admin.quizzes.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // Store logic here
        return redirect()->route('admin.quizzes.index')->with('success', 'Tugas/Postest berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // TODO: Get quiz by id with responses
        return view('admin.quizzes.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get quiz by id
        return view('admin.quizzes.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('admin.quizzes.index')->with('success', 'Tugas/Postest berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Delete quiz questions
            DB::table('quiz_questions')->where('quiz_id', $id)->delete();
            // Delete quiz responses
            DB::table('quiz_responses')->where('quiz_id', $id)->delete();
            // Delete quiz
            DB::table('quizzes')->where('id', $id)->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tugas/Postest berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus tugas/postest'], 500);
        }
    }
}

