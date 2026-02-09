<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;  
use Illuminate\Support\Facades\Log;   
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('data_programs')
            ->select('id', 'program', 'image', 'category', 'start_date', 'type', 'price', 'quota', 'enrolled_count', 'rating', 'created_at');

        // Get categories for filter
        $categories = DB::table('data_programs')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->mapWithKeys(fn($cat) => [$cat => ucfirst($cat)])
            ->toArray();

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Program', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'image', 'label' => 'Gambar', 'type' => 'image'],
                ['key' => 'category', 'label' => 'Kategori', 'sortable' => true],
                ['key' => 'type', 'label' => 'Tipe', 'sortable' => true],
                ['key' => 'price', 'label' => 'Harga', 'sortable' => true, 'type' => 'currency'],
                ['key' => 'start_date', 'label' => 'Tanggal Mulai', 'sortable' => true, 'type' => 'date'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['program', 'category', 'type'],
            'sortable' => ['program', 'category', 'start_date', 'type', 'price', 'created_at'],
            'sortColumns' => [
                'title' => 'program',
            ],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.programs',
            'title' => 'Program Management',
            'entity' => 'program',
            'createLabel' => 'Tambah Program',
            'searchPlaceholder' => 'Cari program, kategori, tipe...',
            'filter' => [
                'key' => 'category',
                'column' => 'category',
                'options' => array_merge(['' => 'Semua Kategori'], $categories)
            ],
            'transformer' => function ($program) {
                $levelCount = DB::table('data_levels')
                    ->where('id_programs', $program->id)
                    ->count();

                $scheduleCount = DB::table('schedules')
                    ->where('id_program', $program->id)
                    ->where('ket', 'Aktif')
                    ->count();

                return [
                    'id' => $program->id,
                    'title' => $program->program,
                    'image' => $program->image,
                    'category' => ucfirst($program->category ?? '-'),
                    'start_date' => $program->start_date ? date('d F Y', strtotime($program->start_date)) : '-',
                    'start_date_raw' => $program->start_date,
                    'type' => ucfirst($program->type ?? '-'),
                    'price' => $program->price ?? 0,
                    'price_raw' => $program->price ?? 0,
                    'level_count' => $levelCount,
                    'schedule_count' => $scheduleCount,
                    'quota' => $program->quota,
                    'enrolled_count' => $program->enrolled_count,
                    'rating' => $program->rating,
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.programs.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all instructors from data_trainers table
        $instructors = DB::table('data_trainers')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('admin.programs.create', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Base validation rules
        $rules = [
            'program' => 'required|string|max:255',
            'category' => 'required|in:Kursus,Pelatihan,Sertifikasi,Outing Class,Outboard',
            'type' => 'required|in:online,offline',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'instructor_id' => 'nullable|exists:data_trainers,id',

            'quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'tools.*' => 'nullable|string',
            'materials.*.title' => 'nullable|string',
            'materials.*.duration' => 'nullable|string',
            'materials.*.description' => 'nullable|string',
            'benefits.*' => 'nullable|string',
        ];

        // Conditional validation
        if ($request->type === 'online') {
            $rules['zoom_link'] = 'required|url';
        } elseif ($request->type === 'offline') {
            $rules['province'] = 'required|string';
            $rules['city'] = 'required|string';
            $rules['district'] = 'required|string';
            $rules['village'] = 'required|string';
            $rules['full_address'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Generate unique slug
        $slug = Str::slug($validated['program']);
        $originalSlug = $slug;
        $count = 1;

        while (DB::table('data_programs')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('programs', $imageName, 'public');
        }

        // Prepare JSON fields
        $tools = $request->tools ? array_values(array_filter($request->tools)) : null;
        $benefits = $request->benefits ? array_values(array_filter($request->benefits)) : null;

        // Process materials - filter out empty objects
        $materials = null;
        if ($request->materials) {
            $materials = array_values(array_filter($request->materials, function ($material) {
                return !empty($material['title']) || !empty($material['duration']) || !empty($material['description']);
            }));
            $materials = !empty($materials) ? $materials : null;
        }

        // Prepare database data
        $data = [
            'program' => $validated['program'],
            'slug' => $slug,
            'description' => $validated['description'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'price' => $validated['price'],
            'instructor_id' => $validated['instructor_id'] ?? null,

            'quota' => $validated['quota'],
            'enrolled_count' => 0,
            'rating' => 0.0,
            'total_reviews' => 0,
            'start_date' => $validated['start_date'],
            'start_time' => $validated['start_time'],
            'end_date' => $validated['end_date'],
            'end_time' => $validated['end_time'],
            'image' => $imagePath,
            'tools' => $tools ? json_encode($tools) : null,
            'learning_materials' => $materials ? json_encode($materials) : null,
            'benefits' => $benefits ? json_encode($benefits) : null,
            'status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Type-specific fields
        if ($validated['type'] === 'online') {
            $data['zoom_link'] = $validated['zoom_link'];
            $data['province'] = null;
            $data['city'] = null;
            $data['district'] = null;
            $data['village'] = null;
            $data['full_address'] = null;
        } else {
            $data['zoom_link'] = null;
            $data['province'] = $validated['province'];
            $data['city'] = $validated['city'];
            $data['district'] = $validated['district'];
            $data['village'] = $validated['village'];
            $data['full_address'] = $validated['full_address'];
        }

        DB::table('data_programs')->insert($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $program = DB::table('data_programs')->where('id', $id)->first();

        if (!$program) {
            return redirect()->route('admin.programs.index')->with('error', 'Program tidak ditemukan');
        }

        // Decode JSON fields
        $program->tools = json_decode($program->tools, true) ?? [];
        $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
        $program->benefits = json_decode($program->benefits, true) ?? [];

        // Get all instructors from data_trainers table
        $instructors = DB::table('data_trainers')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        // Prepare location data for pre-population (if offline)
        $locationData = null;
        if ($program->type === 'offline') {
            $locationData = $this->getLocationIds($program);
        }

        return view('admin.programs.edit', compact('program', 'instructors', 'locationData'));
    }

    // Add this helper method to find IDs from names
    private function getLocationIds($program)
    {
        try {
            $locationIds = [
                'province_id' => null,
                'city_id' => null,
                'district_id' => null,
                'village_id' => null,
            ];

            // Get provinces and find matching ID
            $provinces = Cache::remember('provinces', 3600, function () {
                $response = Http::get('https://gilarya.github.io/data-indonesia/provinsi.json');
                return $response->successful() ? $response->json() : [];
            });

            $province = collect($provinces)->firstWhere('nama', $program->province);
            if (!$province) return $locationIds;
            
            $locationIds['province_id'] = $province['id'];

            // Get cities and find matching ID
            $cities = Cache::remember("cities_{$province['id']}", 3600, function () use ($province) {
                $response = Http::get("https://gilarya.github.io/data-indonesia/kabupaten/{$province['id']}.json");
                return $response->successful() ? $response->json() : [];
            });

            $city = collect($cities)->firstWhere('nama', $program->city);
            if (!$city) return $locationIds;
            
            $locationIds['city_id'] = $city['id'];

            // Get districts and find matching ID
            $districts = Cache::remember("districts_{$city['id']}", 3600, function () use ($city) {
                $response = Http::get("https://gilarya.github.io/data-indonesia/kecamatan/{$city['id']}.json");
                return $response->successful() ? $response->json() : [];
            });

            $district = collect($districts)->firstWhere('nama', $program->district);
            if (!$district) return $locationIds;
            
            $locationIds['district_id'] = $district['id'];

            // Get villages and find matching ID
            $villages = Cache::remember("villages_{$district['id']}", 3600, function () use ($district) {
                $response = Http::get("https://gilarya.github.io/data-indonesia/kelurahan/{$district['id']}.json");
                return $response->successful() ? $response->json() : [];
            });

            $village = collect($villages)->firstWhere('nama', $program->village);
            if ($village) {
                $locationIds['village_id'] = $village['id'];
            }

            return $locationIds;

        } catch (\Exception $e) {
            \Log::error('Error getting location IDs: ' . $e->getMessage());
            return [
                'province_id' => null,
                'city_id' => null,
                'district_id' => null,
                'village_id' => null,
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $program = DB::table('data_programs')->where('id', $id)->first();

        if (!$program) {
            return redirect()->route('admin.programs.index')->with('error', 'Program tidak ditemukan');
        }

        // Base validation
        $rules = [
            'program' => 'required|string|max:255',
            'category' => 'required|in:Kursus,Pelatihan,Sertifikasi,Outing Class,Outboard',
            'type' => 'required|in:online,offline',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'instructor_id' => 'nullable|exists:data_trainers,id',
            'quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'tools.*' => 'nullable|string',
            'materials.*.title' => 'nullable|string',
            'materials.*.duration' => 'nullable|string',
            'materials.*.description' => 'nullable|string',
            'benefits.*' => 'nullable|string',
        ];

        if ($request->type === 'online') {
            $rules['zoom_link'] = 'required|url';
        } elseif ($request->type === 'offline') {
            $rules['province'] = 'required|string';
            $rules['city'] = 'required|string';
            $rules['district'] = 'required|string';
            $rules['village'] = 'required|string';
            $rules['full_address'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Update slug if name changed
        $slug = $program->slug;
        if ($validated['program'] !== $program->program) {
            $slug = Str::slug($validated['program']);
            $originalSlug = $slug;
            $count = 1;

            while (DB::table('data_programs')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        // Handle image upload
        $imagePath = $program->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($program->image) {
                if (Storage::disk('public')->exists($program->image)) {
                    Storage::disk('public')->delete($program->image);
                } elseif (file_exists(public_path($program->image))) {
                    unlink(public_path($program->image));
                }
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('programs', $imageName, 'public');
        }

        // Prepare JSON fields
        $tools = $request->tools ? array_values(array_filter($request->tools)) : null;
        $benefits = $request->benefits ? array_values(array_filter($request->benefits)) : null;

        // Process materials - filter out empty objects
        $materials = null;
        if ($request->materials) {
            $materials = array_values(array_filter($request->materials, function ($material) {
                return !empty($material['title']) || !empty($material['duration']) || !empty($material['description']);
            }));
            $materials = !empty($materials) ? $materials : null;
        }

        // Prepare update data
        $data = [
            'program' => $validated['program'],
            'slug' => $slug,
            'description' => $validated['description'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'price' => $validated['price'],
            'instructor_id' => $validated['instructor_id'] ?? null,

            'quota' => $validated['quota'],
            'start_date' => $validated['start_date'],
            'start_time' => $validated['start_time'],
            'end_date' => $validated['end_date'],
            'end_time' => $validated['end_time'],
            'image' => $imagePath,
            'tools' => $tools ? json_encode($tools) : null,
            'learning_materials' => $materials ? json_encode($materials) : null,
            'benefits' => $benefits ? json_encode($benefits) : null,
            'updated_at' => now(),
        ];

        // Type-specific fields
        if ($validated['type'] === 'online') {
            $data['zoom_link'] = $validated['zoom_link'];
            $data['province'] = null;
            $data['city'] = null;
            $data['district'] = null;
            $data['village'] = null;
            $data['full_address'] = null;
        } else {
            $data['zoom_link'] = null;
            $data['province'] = $validated['province'];
            $data['city'] = $validated['city'];
            $data['district'] = $validated['district'];
            $data['village'] = $validated['village'];
            $data['full_address'] = $validated['full_address'];
        }

        DB::table('data_programs')->where('id', $id)->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $program = DB::table('data_programs')->where('id', $id)->first();

            if (!$program) {
                return response()->json(['success' => false, 'message' => 'Program tidak ditemukan'], 404);
            }

            // Delete image
            if ($program->image) {
                if (Storage::disk('public')->exists($program->image)) {
                    Storage::disk('public')->delete($program->image);
                } elseif (file_exists(public_path($program->image))) {
                    unlink(public_path($program->image));
                }
            }

            DB::table('data_programs')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus program'], 500);
        }
    }
}
