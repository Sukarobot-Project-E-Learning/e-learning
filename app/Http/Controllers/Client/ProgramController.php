<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display listing of all programs
     */
    public function index(Request $request)
    {
        $query = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name',
                'users.avatar as instructor_avatar',
                'users.job as instructor_job'
            )
            ->where('data_programs.status', 'published')
            ->where('data_programs.start_date', '>', now());

        // Get active category from query parameter (for URL state and tab highlighting)
        $activeCategory = $request->get('category', 'all');

        // Load ALL programs for client-side filtering (no server-side filter)
        // This enables instant filtering without page reload

        $programs = $query->orderBy('data_programs.created_at', 'desc')->get();

        //Transform programs data
        $programs->transform(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
            $program->benefits = json_decode($program->benefits, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            return $program;
        });

        return view('client.program.program', compact('programs', 'activeCategory'));
    }

    /**
     * Display program detail
     */
    public function show($slug)
    {
        $program = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->leftJoin('data_trainers', function($join) {
                $join->on('users.email', '=', DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
            })
            ->select(
                'data_programs.*',
                'users.name as instructor_name',
                'users.avatar as instructor_avatar',
                'users.job as instructor_job',
                'data_trainers.bio as instructor_description'
            )
            ->where('data_programs.slug', $slug)
            ->where('data_programs.status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        // Decode JSON fields
        $program->tools = json_decode($program->tools, true) ?? [];
        $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
        $program->benefits = json_decode($program->benefits, true) ?? [];
        $program->available_slots = $program->quota - $program->enrolled_count;

        // Check if user has purchased the program
        $isPurchased = false;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $isPurchased = DB::table('enrollments')
                ->where('student_id', \Illuminate\Support\Facades\Auth::id())
                ->where('program_id', $program->id)
                ->where('status', 'active')
                ->exists();
        }

        return view('client.program.detail-program', compact('program', 'isPurchased'));
    }

    /**
     * Display programs by category (redirect to index with filter)
     */
    public function kursus()
    {
        return redirect()->route('client.program', ['category' => 'kursus']);
    }

    public function pelatihan()
    {
        return redirect()->route('client.program', ['category' => 'pelatihan']);
    }

    public function sertifikasi()
    {
        return redirect()->route('client.program', ['category' => 'sertifikasi']);
    }

    public function outingClass()
    {
        return redirect()->route('client.program', ['category' => 'outing-class']);
    }

    public function outboard()
    {
        return redirect()->route('client.program', ['category' => 'outboard']);
    }

    /**
     * Get programs for home page (popular programs)
     */
    public function getPopularPrograms()
    {
        $programs = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name',
                'users.avatar as instructor_avatar',
                'users.job as instructor_job'
            )
            ->where('data_programs.status', 'published')
            ->where('data_programs.start_date', '>', now())
            ->orderBy('data_programs.rating', 'desc')
            ->orderBy('data_programs.enrolled_count', 'desc')
            ->limit(8)
            ->get();

        return $programs->map(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            return $program;
        });
    }
}
