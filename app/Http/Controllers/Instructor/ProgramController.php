<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Get current instructor/trainer ID
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
     * Display a listing of program submissions.
     * Supports both regular page load and AJAX requests for dynamic filtering
     */
    public function index(Request $request)
    {
        $trainerId = $this->getTrainerId();

        if (!$trainerId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'data' => [],
                    'stats' => ['pending' => 0, 'approved' => 0, 'rejected' => 0],
                    'pagination' => ['current_page' => 1, 'last_page' => 1, 'total' => 0]
                ]);
            }
            
            return view('instructor.programs.index', [
                'submissions' => new LengthAwarePaginator([], 0, 10)
            ]);
        }

        // Base query
        $query = DB::table('program_approvals')
            ->where('instructor_id', $trainerId);

        // Get stats for all submissions (before filtering)
        $allSubmissions = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->get();
        
        $stats = [
            'pending' => $allSubmissions->where('status', 'pending')->count(),
            'approved' => $allSubmissions->where('status', 'approved')->count(),
            'rejected' => $allSubmissions->where('status', 'rejected')->count(),
        ];

        // Apply search filter if provided
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('category', 'LIKE', $searchTerm)
                  ->orWhere('type', 'LIKE', $searchTerm);
            });
        }

        // Apply status filter if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = ['title', 'category', 'type', 'created_at', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }
        
        $query->orderBy($sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');

        // Get per page value
        $perPage = min((int) $request->get('per_page', 10), 100); // Max 100 per page

        // Handle AJAX request for dynamic table updates
        if ($request->ajax() || $request->wantsJson()) {
            $submissions = $query->paginate($perPage);
            
            return response()->json([
                'data' => $submissions->items(),
                'stats' => $stats,
                'pagination' => [
                    'current_page' => $submissions->currentPage(),
                    'last_page' => $submissions->lastPage(),
                    'per_page' => $submissions->perPage(),
                    'total' => $submissions->total(),
                    'from' => $submissions->firstItem(),
                    'to' => $submissions->lastItem(),
                ]
            ]);
        }

        // Regular page load - get all data for client-side filtering
        $submissions = $query->get();

        return view('instructor.programs.index', compact('submissions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('instructor.programs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'type' => 'required|in:online,offline,video',
            'available_slots' => 'nullable|integer|min:1',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tools' => 'nullable|array',
            'materials' => 'nullable|array',
            'benefits' => 'nullable|array',
        ], [
            'title.required' => 'Judul program wajib diisi.',
            'title.max' => 'Judul program maksimal 255 karakter.',
            'type.required' => 'Tipe program wajib dipilih.',
            'type.in' => 'Tipe program harus online, offline, atau video.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $trainerId = $this->getTrainerId();

        if (!$trainerId) {
            return redirect()->route('instructor.programs.create')
                ->with('error', 'Instruktur tidak ditemukan. Silakan hubungi administrator.');
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('programs', $imageName, 'public');
        }

        // Save to program_approvals table (pending approval)
        $programId = DB::table('program_approvals')->insertGetId([
            'instructor_id' => $trainerId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'type' => $validated['type'],
            'price' => $validated['price'] ?? 0,
            'available_slots' => $validated['available_slots'] ?? null,
            'province' => $validated['province'] ?? null,
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'village' => $validated['village'] ?? null,
            'full_address' => $validated['full_address'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'image' => $imagePath,
            'tools' => json_encode($validated['tools'] ?? []),
            'materials' => json_encode($validated['materials'] ?? []),
            'benefits' => json_encode($validated['benefits'] ?? []),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('instructor.programs.index')
            ->with('success', 'Program berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource (view only for approved).
     */
    public function show($id)
    {
        $trainerId = $this->getTrainerId();
        
        $submission = DB::table('program_approvals')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$submission) {
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program tidak ditemukan.');
        }

        // Decode JSON fields safely
        $submission->tools = json_decode($submission->tools ?? '[]', true) ?: [];
        $submission->materials = json_decode($submission->materials ?? '[]', true) ?: [];
        $submission->benefits = json_decode($submission->benefits ?? '[]', true) ?: [];

        return view('instructor.programs.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $trainerId = $this->getTrainerId();
        
        $submission = DB::table('program_approvals')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$submission) {
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program tidak ditemukan.');
        }

        // Check if already approved - redirect to show instead
        if ($submission->status === 'approved') {
            return redirect()->route('instructor.programs.show', $id)
                ->with('info', 'Program yang sudah disetujui tidak dapat diedit.');
        }

        // Decode JSON fields safely
        $submission->tools = json_decode($submission->tools ?? '[]', true) ?: [];
        $submission->materials = json_decode($submission->materials ?? '[]', true) ?: [];
        $submission->benefits = json_decode($submission->benefits ?? '[]', true) ?: [];

        return view('instructor.programs.edit', compact('submission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trainerId = $this->getTrainerId();
        
        $submission = DB::table('program_approvals')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$submission) {
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program tidak ditemukan.');
        }

        // Check if already approved - cannot edit
        if ($submission->status === 'approved') {
            return redirect()->route('instructor.programs.show', $id)
                ->with('error', 'Program yang sudah disetujui tidak dapat diedit.');
        }

        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'type' => 'required|in:online,offline,video',
            'available_slots' => 'nullable|integer|min:1',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'full_address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tools' => 'nullable|array',
            'materials' => 'nullable|array',
            'benefits' => 'nullable|array',
        ], [
            'title.required' => 'Judul program wajib diisi.',
            'title.max' => 'Judul program maksimal 255 karakter.',
            'type.required' => 'Tipe program wajib dipilih.',
            'type.in' => 'Tipe program harus online, offline, atau video.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Handle image upload
        $imagePath = $submission->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($submission->image) {
                if (Storage::disk('public')->exists($submission->image)) {
                    Storage::disk('public')->delete($submission->image);
                } elseif (file_exists(public_path($submission->image))) {
                    @unlink(public_path($submission->image));
                }
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('programs', $imageName, 'public');
        }

        // Update program_approvals
        DB::table('program_approvals')
            ->where('id', $id)
            ->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
                'type' => $validated['type'],
                'price' => $validated['price'] ?? 0,
                'available_slots' => $validated['available_slots'] ?? null,
                'province' => $validated['province'] ?? null,
                'city' => $validated['city'] ?? null,
                'district' => $validated['district'] ?? null,
                'village' => $validated['village'] ?? null,
                'full_address' => $validated['full_address'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'start_time' => $validated['start_time'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'image' => $imagePath,
                'tools' => json_encode($validated['tools'] ?? []),
                'materials' => json_encode($validated['materials'] ?? []),
                'benefits' => json_encode($validated['benefits'] ?? []),
                'status' => 'pending', // Reset to pending after edit
                'rejection_reason' => null, // Clear rejection reason
                'updated_at' => now(),
            ]);

        return redirect()->route('instructor.programs.index')
            ->with('success', 'Program berhasil diperbarui dan menunggu persetujuan ulang.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $trainerId = $this->getTrainerId();
        
        $submission = DB::table('program_approvals')
            ->where('id', $id)
            ->where('instructor_id', $trainerId)
            ->first();

        if (!$submission) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Program tidak ditemukan.'], 404);
            }
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program tidak ditemukan.');
        }

        // Cannot delete approved programs
        if ($submission->status === 'approved') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Program yang sudah disetujui tidak dapat dihapus.'], 403);
            }
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete associated image if exists
        if ($submission->image) {
            if (Storage::disk('public')->exists($submission->image)) {
                Storage::disk('public')->delete($submission->image);
            } elseif (file_exists(public_path($submission->image))) {
                @unlink(public_path($submission->image));
            }
        }

        // Delete the record
        DB::table('program_approvals')->where('id', $id)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Pengajuan program berhasil dihapus.']);
        }

        return redirect()->route('instructor.programs.index')
            ->with('success', 'Pengajuan program berhasil dihapus.');
    }
    
    /**
     * Export submissions to CSV
     */
    public function export(Request $request)
    {
        $trainerId = $this->getTrainerId();
        
        if (!$trainerId) {
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Instruktur tidak ditemukan.');
        }
        
        $submissions = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $filename = 'pengajuan_program_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'ID',
                'Judul',
                'Kategori',
                'Tipe',
                'Harga',
                'Status',
                'Alasan Ditolak',
                'Tanggal Pengajuan',
                'Tanggal Update'
            ]);
            
            // Data rows
            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->title,
                    $submission->category ?? '-',
                    $submission->type,
                    $submission->price ?? 0,
                    $submission->status,
                    $submission->rejection_reason ?? '-',
                    $submission->created_at,
                    $submission->updated_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}