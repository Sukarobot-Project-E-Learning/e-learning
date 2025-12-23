<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     */
    public function index()
    {
        $trainerId = $this->getTrainerId();

        if (!$trainerId) {
            return view('instructor.programs.index', [
                'submissions' => collect([])
            ]);
        }

        // Get all program submissions for this instructor
        $submissions = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('instructor.programs.index', compact('submissions'));
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
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tools' => 'nullable|array',
            'materials' => 'nullable|array',
            'benefits' => 'nullable|array',
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
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/programs'), $imageName);
            $imagePath = 'uploads/programs/' . $imageName;
        }

        // Save to program_approvals table (pending approval)
        DB::table('program_approvals')->insert([
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

        // Decode JSON fields
        $submission->tools = json_decode($submission->tools ?? '[]', true);
        $submission->materials = json_decode($submission->materials ?? '[]', true);
        $submission->benefits = json_decode($submission->benefits ?? '[]', true);

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

        // Decode JSON fields
        $submission->tools = json_decode($submission->tools ?? '[]', true);
        $submission->materials = json_decode($submission->materials ?? '[]', true);
        $submission->benefits = json_decode($submission->benefits ?? '[]', true);

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
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tools' => 'nullable|array',
            'materials' => 'nullable|array',
            'benefits' => 'nullable|array',
        ]);

        // Handle image upload
        $imagePath = $submission->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/programs'), $imageName);
            $imagePath = 'uploads/programs/' . $imageName;
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
    public function destroy($id)
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

        // Cannot delete approved programs
        if ($submission->status === 'approved') {
            return redirect()->route('instructor.programs.index')
                ->with('error', 'Program yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete
        DB::table('program_approvals')->where('id', $id)->delete();

        return redirect()->route('instructor.programs.index')
            ->with('success', 'Pengajuan program berhasil dihapus.');
    }
}
