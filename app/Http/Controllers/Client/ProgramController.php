<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseAssignment;
use App\Models\CourseSubmission;
use App\Models\CourseProgress;
use Artesaos\SEOTools\Facades\SEOTools;

class ProgramController extends Controller
{
    /**
     * Display listing of all programs
     */
    public function index(Request $request)
    {
        $query = DB::table('data_programs')
            ->leftJoin('data_trainers', 'data_programs.instructor_id', '=', 'data_trainers.id')
            ->leftJoin('users', function ($join) {
                $join->on('users.email', '=', DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
            })
            ->select(
                'data_programs.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.foto as instructor_foto',
                'users.avatar as instructor_user_avatar',
                'data_trainers.pekerjaan as instructor_job'
            )
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
            $program->instructor_avatar = $this->resolveInstructorAvatar(
                $program->instructor_foto ?? null,
                $program->instructor_user_avatar ?? null,
                $program->instructor_name ?? null
            );
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
            ->leftJoin('data_trainers', 'data_programs.instructor_id', '=', 'data_trainers.id')
            ->leftJoin('users', function ($join) {
                $join->on('users.email', '=', DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
            })
            ->select(
                'data_programs.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.foto as instructor_foto',
                'users.avatar as instructor_user_avatar',
                'data_trainers.pekerjaan as instructor_job',
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
        $program->instructor_avatar = $this->resolveInstructorAvatar(
            $program->instructor_foto ?? null,
            $program->instructor_user_avatar ?? null,
            $program->instructor_name ?? null
        );
        $isCourseProgram = $this->isCourseCategory($program->category ?? null);

        // Check enrollment status for CTA rendering.
        $isPurchased = false;
        $enrollment = null;
        if (Auth::check()) {
            $enrollment = $this->getActiveEnrollment($program->id, Auth::id());
            $isPurchased = $enrollment !== null;
        }

        $curriculumSections = [];
        $materialCount = count($program->learning_materials);

        if ($isCourseProgram) {
            $syllabusItems = $this->buildSyllabusItems(
                $this->getProgramLessons($program->id),
                $program->id,
                $isPurchased ? Auth::id() : null
            );

            if (!$isPurchased) {
                $syllabusItems = array_map(function (array $item) {
                    $item['is_locked'] = true;
                    $item['is_unlocked'] = false;
                    $item['is_current'] = false;
                    return $item;
                }, $syllabusItems);
            }

            $curriculumSections = $this->groupSyllabusItemsBySection($syllabusItems);
            $materialCount = count($syllabusItems);
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

        // Get recommended vouchers
        $recommendedVouchers = \App\Models\Voucher::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', now()->toDateString());
            })
            ->get()
            ->filter(function ($voucher) {
                return $voucher->isValid();
            })
            ->take(2);

        $ratingSummary = DB::table('program_proofs')
            ->where('program_id', $program->id)
            ->whereNotNull('rating')
            ->selectRaw('AVG(rating) as avg_rating, COUNT(rating) as total_ratings')
            ->first();

        $avgRating = $ratingSummary && $ratingSummary->avg_rating
            ? round((float) $ratingSummary->avg_rating, 1)
            : 0.0;
        $totalRatings = (int) ($ratingSummary->total_ratings ?? 0);

        $skillLevel = $materialCount <= 5
            ? 'Beginner'
            : ($materialCount <= 12 ? 'Intermediate' : 'Advanced');

        $detailView = $isCourseProgram
            ? 'client.program.detail-kursus'
            : 'client.program.detail-program';

        return view($detailView, compact(
            'program',
            'isPurchased',
            'recommendedVouchers',
            'isCourseProgram',
            'avgRating',
            'totalRatings',
            'skillLevel',
            'curriculumSections'
        ));
    }

    /**
     * LMS classroom page for purchased users.
     */
    public function classroom(Request $request, string $slug)
    {
        $program = DB::table('data_programs')
            ->select('id', 'slug', 'program', 'description', 'category', 'image', 'type')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        if (!$this->isCourseCategory($program->category ?? null)) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Halaman kelas hanya tersedia untuk program kursus.');
        }

        $enrollment = $this->getActiveEnrollment($program->id, Auth::id());
        if (!$enrollment) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Anda belum terdaftar pada kelas ini.');
        }

        $lessons = $this->getProgramLessons($program->id);

        $syllabusItems = $this->buildSyllabusItems(
            $lessons,
            $program->id,
            Auth::id()
        );

        $selectedItem = $this->resolveSelectedSyllabusItem(
            $syllabusItems,
            (int) $request->query('item', 0)
        );

        $totalItems = count($syllabusItems);
        $completedCount = collect($syllabusItems)->where('is_done', true)->count();
        $progressPercent = $totalItems > 0
            ? (int) round(($completedCount / $totalItems) * 100)
            : 0;
        $isCourseCompleted = $totalItems > 0 && $completedCount >= $totalItems;

        $assignments = CourseAssignment::where('program_id', $program->id)
            ->orderBy('created_at')
            ->get();

        $assignmentSubmissions = collect();
        if ($assignments->isNotEmpty()) {
            $assignmentSubmissions = CourseSubmission::where('user_id', Auth::id())
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->get()
                ->keyBy('assignment_id');
        }

        $hasAssignments = $assignments->isNotEmpty();

        SEOTools::setTitle('Kelas - ' . $program->program);
        SEOTools::setDescription('Lanjutkan pembelajaran Anda pada kelas ' . $program->program . '.');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());

        return view('client.program.classroom', compact(
            'program',
            'syllabusItems',
            'selectedItem',
            'progressPercent',
            'completedCount',
            'totalItems',
            'isCourseCompleted',
            'assignments',
            'assignmentSubmissions',
            'hasAssignments'
        ));
    }

    /**
     * Post-test page shown after user finishes all kursus materials.
     */
    public function posttest(string $slug)
    {
        $program = DB::table('data_programs')
            ->select('id', 'slug', 'program', 'description', 'category', 'image', 'type')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        if (!$this->isCourseCategory($program->category ?? null)) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Halaman post-test hanya tersedia untuk program kursus.');
        }

        $enrollment = $this->getActiveEnrollment($program->id, Auth::id());
        if (!$enrollment) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Anda belum terdaftar pada kelas ini.');
        }

        $lessons = $this->getProgramLessons($program->id);
        $syllabusItems = $this->buildSyllabusItems(
            $lessons,
            $program->id,
            Auth::id()
        );

        $totalItems = count($syllabusItems);
        $completedCount = collect($syllabusItems)->where('is_done', true)->count();
        $progressPercent = $totalItems > 0
            ? (int) round(($completedCount / $totalItems) * 100)
            : 0;
        $isCourseCompleted = $totalItems > 0 && $completedCount >= $totalItems;

        if (!$isCourseCompleted) {
            return redirect()->route('client.program.classroom', ['slug' => $slug])
                ->with('error', 'Selesaikan semua materi terlebih dahulu untuk membuka post-test.');
        }

        $assignments = CourseAssignment::where('program_id', $program->id)
            ->orderBy('created_at')
            ->get();

        $assignmentSubmissions = collect();
        if ($assignments->isNotEmpty()) {
            $assignmentSubmissions = CourseSubmission::where('user_id', Auth::id())
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->get()
                ->keyBy('assignment_id');
        }

        $hasAssignments = $assignments->isNotEmpty();

        SEOTools::setTitle('Post-Test - ' . $program->program);
        SEOTools::setDescription('Kerjakan post-test untuk kelas ' . $program->program . '.');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());

        $selectedItem = null;
        $isPosttestPage = true;

        return view('client.program.posttest', compact(
            'program',
            'syllabusItems',
            'selectedItem',
            'progressPercent',
            'completedCount',
            'totalItems',
            'isCourseCompleted',
            'assignments',
            'assignmentSubmissions',
            'hasAssignments',
            'isPosttestPage'
        ));
    }

    /**
     * Completion page shown after user finishes all kursus materials.
     */
    public function courseCompleted(string $slug)
    {
        $program = DB::table('data_programs')
            ->select('id', 'slug', 'program', 'category', 'image')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        if (!$this->isCourseCategory($program->category ?? null)) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Halaman penyelesaian hanya tersedia untuk program kursus.');
        }

        $enrollment = $this->getActiveEnrollment($program->id, Auth::id());
        if (!$enrollment) {
            return redirect()->route('client.program.detail', ['slug' => $slug])
                ->with('error', 'Anda belum terdaftar pada kelas ini.');
        }

        $lessons = $this->getProgramLessons($program->id);
        $totalItems = count($lessons);
        $completedLessonIds = $this->getCompletedLessonIdsForUser($program->id, Auth::id());
        $completedCount = $this->computeContiguousCompletedCount($lessons, $completedLessonIds);
        $isCompleted = $totalItems > 0 && $completedCount >= $totalItems;

        if (!$isCompleted) {
            return redirect()->route('client.program.classroom', ['slug' => $slug])
                ->with('error', 'Selesaikan semua materi terlebih dahulu untuk membuka halaman ini.');
        }

        $proof = DB::table('program_proofs')
            ->where('student_id', Auth::id())
            ->where('program_id', $program->id)
            ->first();

        return view('client.program.course-complete', compact('program', 'proof'));
    }

    /**
     * Mark a syllabus item as completed with strict sequential locking.
     */
    public function markMaterialComplete(Request $request, string $slug, int $index)
    {
        if ($index < 1) {
            abort(404, 'Materi tidak ditemukan');
        }

        $program = DB::table('data_programs')
            ->select('id', 'slug', 'category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        if (!$this->isCourseCategory($program->category ?? null)) {
            return back()->with('error', 'Progress materi hanya tersedia untuk program kursus.');
        }

        $lessons = $this->getProgramLessons($program->id);
        $totalItems = count($lessons);
        if ($totalItems === 0 || $index > $totalItems) {
            abort(404, 'Materi tidak valid');
        }

        $lessonId = (int) ($lessons[$index - 1]['lesson_id'] ?? 0);
        if ($lessonId <= 0) {
            abort(404, 'Materi tidak valid');
        }

        $alreadyCompleted = false;
        $courseJustCompleted = false;

        try {
            DB::transaction(function () use ($program, $index, $totalItems, $lessons, $lessonId, &$alreadyCompleted, &$courseJustCompleted) {
                $enrollment = $this->getActiveEnrollment($program->id, Auth::id(), true);

                if (!$enrollment) {
                    throw new \RuntimeException('Anda belum terdaftar di kelas ini.');
                }

                $completedLessonIds = $this->getCompletedLessonIdsForUser(
                    $program->id,
                    Auth::id(),
                    true
                );
                $lastCompletedOrder = $this->computeContiguousCompletedCount($lessons, $completedLessonIds);

                if (in_array($lessonId, $completedLessonIds, true)) {
                    $alreadyCompleted = true;
                    return;
                }

                $expectedNext = $lastCompletedOrder + 1;
                if ($index !== $expectedNext) {
                    throw new \InvalidArgumentException('Materi ini masih terkunci. Selesaikan materi sebelumnya terlebih dahulu.');
                }

                CourseProgress::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'lesson_id' => $lessonId,
                    ],
                    [
                        'is_completed' => true,
                        'completed_at' => now(),
                    ]
                );

                $completedLessonIds[] = $lessonId;
                $completedLessonIds = array_values(array_unique($completedLessonIds));

                $contiguous = $this->computeContiguousCompletedCount($lessons, $completedLessonIds);
                $courseJustCompleted = $contiguous >= $totalItems;
            });
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($courseJustCompleted) {
            return redirect()->route('client.program.course-complete', ['slug' => $slug])
                ->with('success', 'Selamat! Anda telah menyelesaikan semua materi kursus.');
        }

        if ($alreadyCompleted) {
            return $this->redirectAfterSyllabusUpdate(
                $request,
                'Materi ini sudah ditandai selesai sebelumnya.'
            );
        }

        return $this->redirectAfterSyllabusUpdate($request);
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
            ->leftJoin('data_trainers', 'data_programs.instructor_id', '=', 'data_trainers.id')
            ->leftJoin('users', function ($join) {
                $join->on('users.email', '=', DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
            })
            ->select(
                'data_programs.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.foto as instructor_foto',
                'users.avatar as instructor_user_avatar',
                'data_trainers.pekerjaan as instructor_job'
            )
            ->where('data_programs.status', 'published')
            // ->where('data_programs.start_date', '>', now()) // Removed to show all programs
            ->orderBy('data_programs.rating', 'desc')
            ->orderBy('data_programs.enrolled_count', 'desc')
            ->limit(8)
            ->get();

        return $programs->map(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            $program->instructor_avatar = $this->resolveInstructorAvatar(
                $program->instructor_foto ?? null,
                $program->instructor_user_avatar ?? null,
                $program->instructor_name ?? null
            );
            return $program;
        });
    }

    /**
     * Resolve instructor avatar URL.
     * Priority: data_trainers.foto > users.avatar > ui-avatars fallback.
     * Handles external URLs, public path, and storage path (same logic as InstructorController & User model).
     */
    private function resolveInstructorAvatar(?string $foto, ?string $userAvatar, ?string $name): string
    {
        $defaultAvatar = asset('assets/elearning/client/img/default-avatar.jpeg');

        // 1. Try data_trainers.foto first
        if ($foto) {
            if (filter_var($foto, FILTER_VALIDATE_URL)) {
                return $foto;
            }
            if (file_exists(public_path($foto))) {
                return asset($foto);
            }
            if (file_exists(storage_path('app/public/' . $foto))) {
                return asset('storage/' . $foto);
            }
        }

        // 2. Fallback to users.avatar
        if ($userAvatar) {
            if (filter_var($userAvatar, FILTER_VALIDATE_URL)) {
                return $userAvatar;
            }
            if (file_exists(public_path($userAvatar))) {
                return asset($userAvatar);
            }
            if (file_exists(public_path('storage/' . $userAvatar))) {
                return asset('storage/' . $userAvatar);
            }
            if (file_exists(storage_path('app/public/' . $userAvatar))) {
                return asset('storage/' . $userAvatar);
            }
        }

        // 3. Fallback to UI Avatars (name-based) or default
        if ($name) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random';
        }

        return $defaultAvatar;
    }

    /**
     * Fetch active enrollment, optionally with row-level lock inside transaction.
     */
    private function getActiveEnrollment(int $programId, int $studentId, bool $lockForUpdate = false)
    {
        $query = DB::table('enrollments')
            ->where('student_id', $studentId)
            ->where('program_id', $programId)
            ->where('status', 'active');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    /**
     * Pick selected lesson safely from query parameter and unlock state.
     */
    private function resolveSelectedSyllabusItem(array $syllabusItems, int $requestedIndex): ?array
    {
        if (empty($syllabusItems)) {
            return null;
        }

        if ($requestedIndex > 0) {
            $requestedItem = collect($syllabusItems)
                ->firstWhere('index', $requestedIndex);

            if ($requestedItem && !$requestedItem['is_locked']) {
                return $requestedItem;
            }
        }

        $currentItem = collect($syllabusItems)->firstWhere('is_current', true);
        if ($currentItem) {
            return $currentItem;
        }

        $firstUnlocked = collect($syllabusItems)->firstWhere('is_unlocked', true);

        return $firstUnlocked ?? $syllabusItems[0];
    }

    /**
     * Redirect back or to a safe internal path after updating syllabus state.
     */
    private function redirectAfterSyllabusUpdate(Request $request, ?string $message = null)
    {
        $redirectTo = $request->input('redirect_to');

        if (is_string($redirectTo) && str_starts_with($redirectTo, '/')) {
            if ($message) {
                return redirect($redirectTo)->with('success', $message);
            }

            return redirect($redirectTo);
        }

        if ($message) {
            return back()->with('success', $message);
        }

        return back();
    }

    /**
     * Build syllabus state from ordered LMS lessons and enrollment progress.
     */
    private function buildSyllabusItems(array $lessons, int $programId, ?int $userId): array
    {
        $completedLessonIds = [];
        $lastCompletedOrder = 0;
        $hasProgress = $userId !== null;

        if ($hasProgress) {
            $completedLessonIds = $this->getCompletedLessonIdsForUser($programId, $userId);
            $lastCompletedOrder = $this->computeContiguousCompletedCount($lessons, $completedLessonIds);
        }

        $items = [];
        foreach ($lessons as $rawIndex => $lesson) {
            $index = $rawIndex + 1;
            $lessonId = (int) ($lesson['lesson_id'] ?? 0);
            $isDone = $lessonId > 0 && in_array($lessonId, $completedLessonIds, true);
            $isUnlocked = $hasProgress ? ($index === 1 || $lastCompletedOrder >= ($index - 1)) : true;
            $isCurrent = $hasProgress && $isUnlocked && !$isDone && $index === ($lastCompletedOrder + 1);

            $items[] = [
                'index' => $index,
                'section_id' => $lesson['section_id'] ?? null,
                'section_title' => $lesson['section_title'] ?? 'Bab Tanpa Judul',
                'lesson_id' => $lesson['lesson_id'] ?? null,
                'title' => $lesson['title'] ?? ('Materi ' . $index),
                'duration' => null,
                'description' => $lesson['content'] ?? null,
                'type' => $lesson['type'] ?? 'text',
                'content' => $lesson['content'] ?? null,
                'video_url' => $lesson['video_url'] ?? null,
                'is_done' => $isDone,
                'is_unlocked' => $isUnlocked,
                'is_current' => $isCurrent,
                'is_locked' => !$isUnlocked,
            ];
        }

        return $items;
    }

    /**
     * Get ordered LMS lessons for a program, grouped by section order.
     */
    private function getProgramLessons(int $programId): array
    {
        $sections = \App\Models\CourseSection::with([
            'lessons' => function ($query) {
                $query->orderBy('order', 'asc');
            },
        ])
            ->where('program_id', $programId)
            ->orderBy('order', 'asc')
            ->get();

        $lessons = [];
        foreach ($sections as $section) {
            foreach ($section->lessons as $lesson) {
                $lessons[] = [
                    'section_id' => $section->id,
                    'section_title' => $section->title,
                    'lesson_id' => $lesson->id,
                    'title' => $lesson->title,
                    'type' => $lesson->type,
                    'content' => $lesson->content,
                    'video_url' => $lesson->video_url,
                ];
            }
        }

        return $lessons;
    }

    /**
     * Group syllabus items into section-based curriculum shape.
     */
    private function groupSyllabusItemsBySection(array $syllabusItems): array
    {
        if (empty($syllabusItems)) {
            return [];
        }

        return collect($syllabusItems)
            ->groupBy(function (array $item) {
                return $item['section_id'] ?? 'no-section';
            })
            ->map(function ($items, $sectionId) {
                $first = $items->first();

                return [
                    'id' => $sectionId,
                    'title' => $first['section_title'] ?? 'Bab Tanpa Judul',
                    'lessons' => $items->map(function (array $item) {
                        return [
                            'id' => $item['lesson_id'] ?? null,
                            'index' => $item['index'],
                            'title' => $item['title'],
                            'type' => $item['type'],
                            'content' => $item['content'] ?? null,
                            'video_url' => $item['video_url'] ?? null,
                            'is_locked' => (bool) ($item['is_locked'] ?? false),
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Fetch completed lesson IDs for a user within a program.
     */
    private function getCompletedLessonIdsForUser(int $programId, int $userId, bool $lockForUpdate = false): array
    {
        $query = DB::table('lms_progresses as progress')
            ->join('lms_lessons as lessons', 'progress.lesson_id', '=', 'lessons.id')
            ->join('lms_sections as sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.program_id', $programId)
            ->where('progress.user_id', $userId)
            ->where('progress.is_completed', true)
            ->select('progress.lesson_id');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $lessonIds = $query->get()->pluck('lesson_id')->map(fn ($id) => (int) $id)->all();
        $lessonIds = array_values(array_unique($lessonIds));

        return $lessonIds;
    }

    private function computeContiguousCompletedCount(array $lessons, array $completedLessonIds): int
    {
        if (empty($lessons) || empty($completedLessonIds)) {
            return 0;
        }

        $completedLookup = array_fill_keys($completedLessonIds, true);
        $contiguous = 0;

        foreach ($lessons as $lesson) {
            $lessonId = (int) ($lesson['lesson_id'] ?? 0);
            if ($lessonId > 0 && isset($completedLookup[$lessonId])) {
                $contiguous++;
                continue;
            }

            break;
        }

        return $contiguous;
    }

    /**
     * Determine whether a program belongs to Kursus category.
     */
    private function isCourseCategory(?string $category): bool
    {
        return strtolower(trim((string) $category)) === 'kursus';
    }
}
