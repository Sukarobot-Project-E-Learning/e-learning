<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseSubmission;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    /**
     * Get current instructor/trainer ID.
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
     * Assignment & Post-test management UI.
     */
    public function index()
    {
        $trainerId = $this->getTrainerId();

        $programs = Program::query()
            ->where('instructor_id', $trainerId)
            ->select('id', 'program')
            ->orderBy('program')
            ->get();

        return view('instructor.assignments.index', compact('programs'));
    }

    /**
     * Post-test dashboard for courses.
     */
    public function dashboard(Request $request)
    {
        $trainerId = $this->getTrainerId();

        $programs = Program::query()
            ->where('instructor_id', $trainerId)
            ->select('id', 'program')
            ->orderBy('program')
            ->get();

        $selectedProgramId = $request->get('program_id')
            ?: optional($programs->first())->id;

        if (!$selectedProgramId) {
            return view('instructor.assignments.dashboard', [
                'programs' => $programs,
                'selectedProgramId' => null,
                'stats' => [
                    'total_students' => 0,
                    'submitted' => 0,
                    'completion_rate' => 0,
                    'avg_score' => 0,
                ],
                'submittedStudents' => collect(),
                'pendingStudents' => collect(),
                'recentSubmissions' => collect(),
            ]);
        }

        $assignmentIds = CourseAssignment::query()
            ->where('program_id', $selectedProgramId)
            ->where(function ($query) {
                $query->where('type', 'post-test')
                    ->orWhereNull('type');
            })
            ->pluck('id');

        $submittedStudentIds = CourseSubmission::query()
            ->whereIn('assignment_id', $assignmentIds)
            ->distinct('user_id')
            ->pluck('user_id');

        $totalStudents = DB::table('enrollments')
            ->where('program_id', $selectedProgramId)
            ->where('status', 'active')
            ->distinct('student_id')
            ->count('student_id');

        $submittedCount = $submittedStudentIds->count();
        $completionRate = $totalStudents > 0
            ? round(($submittedCount / $totalStudents) * 100, 1)
            : 0;

        $avgScore = CourseSubmission::query()
            ->whereIn('assignment_id', $assignmentIds)
            ->avg('score');

        $submittedStudents = DB::table('users')
            ->whereIn('id', $submittedStudentIds)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        $pendingStudents = DB::table('enrollments')
            ->join('users', 'enrollments.student_id', '=', 'users.id')
            ->where('enrollments.program_id', $selectedProgramId)
            ->where('enrollments.status', 'active')
            ->whereNotIn('enrollments.student_id', $submittedStudentIds)
            ->select('users.id', 'users.name', 'users.email')
            ->distinct()
            ->orderBy('users.name')
            ->get();

        $recentSubmissions = CourseSubmission::query()
            ->with(['user', 'assignment'])
            ->whereIn('assignment_id', $assignmentIds)
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_students' => $totalStudents,
            'submitted' => $submittedCount,
            'completion_rate' => $completionRate,
            'avg_score' => round($avgScore ?? 0, 1),
        ];

        return view('instructor.assignments.dashboard', [
            'programs' => $programs,
            'selectedProgramId' => $selectedProgramId,
            'stats' => $stats,
            'submittedStudents' => $submittedStudents,
            'pendingStudents' => $pendingStudents,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }
}
