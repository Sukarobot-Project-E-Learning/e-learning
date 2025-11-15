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
        // Get current instructor/trainer ID
        // TODO: Get from authenticated user or session
        // For now, we'll get trainer ID from data_trainers based on email or use first active trainer
        $trainerId = null;
        
        // If user is authenticated and has trainer relation
        if (auth()->check()) {
            $user = auth()->user();
            // Try to find trainer by email
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();
            
            if ($trainer) {
                $trainerId = $trainer->id;
            }
        }
        
        // If no trainer found, use first active trainer (for testing)
        if (!$trainerId) {
            $trainer = DB::table('data_trainers')
                ->where('status_trainer', 'Aktif')
                ->first();
            $trainerId = $trainer ? $trainer->id : null;
        }

        if (!$trainerId) {
            // No trainer found, return with empty data
            return view('instructor.dashboard', [
                'totalPrograms' => 0,
                'totalQuizzes' => 0,
                'totalStudents' => 0,
                'totalSubmissions' => 0,
                'recentPrograms' => collect([])
            ]);
        }

        // Get programs assigned to this trainer (from schedules)
        $programIds = DB::table('schedules')
            ->where('id_trainer', $trainerId)
            ->where('ket', 'Aktif')
            ->distinct()
            ->pluck('id_program')
            ->filter()
            ->toArray();

        $totalPrograms = count($programIds);
        
        // Get program details
        $programs = DB::table('data_programs')
            ->whereIn('id', $programIds)
            ->get();

        // Get total students from schedules (unique students in big_data)
        $scheduleIds = DB::table('schedules')
            ->where('id_trainer', $trainerId)
            ->where('ket', 'Aktif')
            ->pluck('id_bigData')
            ->filter()
            ->toArray();

        $uniqueStudentIds = DB::table('big_data')
            ->whereIn('id_bigData', $scheduleIds)
            ->distinct()
            ->pluck('id_siswa')
            ->filter()
            ->count();

        $totalStudents = $uniqueStudentIds;

        // Get total quizzes/tugas for this instructor
        $totalQuizzes = DB::table('quizzes')
            ->where('instructor_id', $trainerId)
            ->count();
        
        // Get total submissions/responses for quizzes created by this instructor
        $quizIds = DB::table('quizzes')
            ->where('instructor_id', $trainerId)
            ->pluck('id')
            ->toArray();
        
        $totalSubmissions = 0;
        if (!empty($quizIds)) {
            $totalSubmissions = DB::table('quiz_responses')
                ->whereIn('quiz_id', $quizIds)
                ->count();
        }

        // Get recent programs with details
        $recentPrograms = DB::table('schedules')
            ->where('id_trainer', $trainerId)
            ->where('ket', 'Aktif')
            ->join('data_programs', 'schedules.id_program', '=', 'data_programs.id')
            ->select('data_programs.id', 'data_programs.program as title', 'schedules.ket as status')
            ->distinct()
            ->orderBy('schedules.created_at', 'desc')
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

