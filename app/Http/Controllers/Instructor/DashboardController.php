<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display instructor dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // TODO: Add statistics queries
        // $totalPrograms = Program::where('instructor_id', auth()->id())->count();
        // $totalQuizzes = Quiz::where('instructor_id', auth()->id())->count();
        // $totalStudents = Enrollment::whereHas('program', function($q) {
        //     $q->where('instructor_id', auth()->id());
        // })->count();

        return view('instructor.dashboard');
    }
}

