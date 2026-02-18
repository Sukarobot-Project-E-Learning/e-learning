<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\SEOTools;

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
            ->where('data_programs.status', 'published');
            // ->where('data_programs.start_date', '>', now()); // Removed to show all programs

        SEOTools::setTitle('Program Kami');
        SEOTools::setDescription('Temukan berbagai program belajar robotika dan coding yang sesuai dengan kebutuhan Anda. Mulai dari kursus, pelatihan, hingga sertifikasi.');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::twitter()->setSite('@sukarobot');

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

        SEOTools::setTitle($program->program);
        SEOTools::setDescription(\Illuminate\Support\Str::limit(strip_tags($program->description), 150));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'article');
        
        if ($program->image) {
             $imgUrl = ($program->image && str_starts_with($program->image, 'images/'))
                ? asset($program->image) 
                : asset('storage/' . $program->image);
            SEOTools::addImages($imgUrl);
            SEOTools::jsonLd()->addImage($imgUrl);
        }

        // Add JSON-LD for Course Schema
        SEOTools::jsonLd()->addValue('@context', 'https://schema.org');
        SEOTools::jsonLd()->addValue('@type', 'Course');
        SEOTools::jsonLd()->addValue('name', $program->program);
        SEOTools::jsonLd()->addValue('description', \Illuminate\Support\Str::limit(strip_tags($program->description), 150));
        SEOTools::jsonLd()->addValue('provider', [
            '@type' => 'Organization',
            'name' => 'Sukarobot Academy',
            'sameAs' => url('/')
        ]);
        
        // Add Offer/Pricing
        if ($program->price >= 0) {
             SEOTools::jsonLd()->addValue('offers', [
                '@type' => 'Offer',
                'price' => $program->price,
                'priceCurrency' => 'IDR',
                'availability' => $program->available_slots > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => url()->current(),
                'category' => $program->category
            ]);
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
            ->where('data_programs.status', 'published')
            // ->where('data_programs.start_date', '>', now()) // Removed to show all programs
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
