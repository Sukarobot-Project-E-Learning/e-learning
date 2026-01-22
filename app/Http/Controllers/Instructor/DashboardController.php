<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display instructor dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current logged in user ID
        $userId = auth()->id();
        
        if (!$userId) {
             return redirect()->route('login');
        }

        $trainer = DB::table('data_trainers')->where('email', auth()->user()->email)->first();
        $trainerId = $trainer ? $trainer->id : null;

        // Get total programs created by this instructor
        $totalPrograms = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->where('status', 'approved')
            ->count();
        
        // Get total students enrolled in instructor's programs
        $totalStudents = DB::table('enrollments')
            ->join('data_programs', 'enrollments.program_id', '=', 'data_programs.id')
            ->where(function($query) use ($userId, $trainerId) {
                $query->where('data_programs.instructor_id', $userId);
                if ($trainerId) {
                    $query->orWhere('data_programs.instructor_id', $trainerId);
                }
            })
            ->where('enrollments.status', 'active')
            ->distinct('enrollments.student_id')
            ->count('enrollments.student_id');

        // Get total quizzes/tugas for this instructor
        // Assuming instructor_id in quizzes refers to users.id or data_trainers.id
        // We will check both or assume users.id based on program logic.
        // If quizzes uses data_trainers, we might need that lookup back.
        // Let's assume it uses users.id for consistency with new system, 
        // OR we try to find the trainer ID just in case.

        $totalQuizzes = DB::table('quizzes')
            ->where(function($query) use ($userId, $trainerId) {
                $query->where('instructor_id', $userId);
                if ($trainerId) {
                    $query->orWhere('instructor_id', $trainerId);
                }
            })
            ->count();
        
        // Get total submissions
        $quizIds = DB::table('quizzes')
            ->where(function($query) use ($userId, $trainerId) {
                $query->where('instructor_id', $userId);
                if ($trainerId) {
                    $query->orWhere('instructor_id', $trainerId);
                }
            })
            ->pluck('id')
            ->toArray();
        
        $totalSubmissions = 0;
        if (!empty($quizIds)) {
            $totalSubmissions = DB::table('quiz_responses')
                ->whereIn('quiz_id', $quizIds)
                ->count();
        }

        // Get recent programs
        $recentPrograms = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->where('status', 'approved')
            ->select('id', 'title as program', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('instructor.dashboard', compact(
            'totalPrograms',
            'totalQuizzes',
            'totalStudents',
            'totalSubmissions',
            'recentPrograms'
        ));
    }
}

