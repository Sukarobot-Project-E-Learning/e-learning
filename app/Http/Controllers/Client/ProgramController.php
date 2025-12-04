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
            ->where('data_programs.status', 'published');

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('data_programs.category', $request->category);
        }

        $programs = $query->orderBy('data_programs.created_at', 'desc')->paginate(12);

        //Transform programs data
        $programs->getCollection()->transform(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
            $program->benefits = json_decode($program->benefits, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            return $program;
        });

        return view('client.program.program', compact('programs'));
    }

    /**
     * Display program detail
     */
    public function show($slug)
    {
        $program = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name',
                'users.avatar as instructor_avatar',
                'users.job as instructor_job'
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

        return view('client.program.detail-program', compact('program'));
    }

    /**
     * Display programs by category
     */
    public function kursus()
    {
        return $this->programsByCategory('kursus');
    }

    public function pelatihan()
    {
        return $this->programsByCategory('pelatihan');
    }

    public function sertifikasi()
    {
        return $this->programsByCategory('sertifikasi');
    }

    public function outingClass()
    {
        return $this->programsByCategory('outing-class');
    }

    public function outboard()
    {
        return $this->programsByCategory('outboard');
    }

    /**
     * Helper method to get programs by category
     */
    private function programsByCategory($category)
    {
        $programs = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name',
                'users.avatar as instructor_avatar',
                'users.job as instructor_job'
            )
            ->where('data_programs.category', $category)
            ->where('data_programs.status', 'published')
            ->orderBy('data_programs.created_at', 'desc')
            ->paginate(12);

        $programs->getCollection()->transform(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
            $program->benefits = json_decode($program->benefits, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            return $program;
        });

        $categoryName = ucwords(str_replace('-', ' ', $category));

        return view('client.program.' . str_replace('-', '', $category), compact('programs', 'categoryName'));
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
