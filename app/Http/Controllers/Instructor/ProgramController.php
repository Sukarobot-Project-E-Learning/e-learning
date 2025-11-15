<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get current instructor/trainer ID
        $trainerId = null;
        
        if (auth()->check()) {
            $user = auth()->user();
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();
            
            if ($trainer) {
                $trainerId = $trainer->id;
            }
        }
        
        if (!$trainerId) {
            $trainer = DB::table('data_trainers')
                ->where('status_trainer', 'Aktif')
                ->first();
            $trainerId = $trainer ? $trainer->id : null;
        }

        if (!$trainerId) {
            return view('instructor.programs.index', [
                'programs' => collect([]),
                'pendingApprovals' => collect([])
            ]);
        }

        // Get approved programs from schedules where this trainer is assigned
        $programIds = DB::table('schedules')
            ->where('id_trainer', $trainerId)
            ->where('ket', 'Aktif')
            ->distinct()
            ->pluck('id_program')
            ->filter()
            ->toArray();

        // Get program details with pagination (5 per page)
        $programs = DB::table('data_programs')
            ->whereIn('id', $programIds)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $programs->getCollection()->transform(function($program) use ($trainerId) {
            // Get schedule info for this program
            $schedule = DB::table('schedules')
                ->where('id_trainer', $trainerId)
                ->where('id_program', $program->id)
                ->where('ket', 'Aktif')
                ->first();

            return [
                'id' => $program->id,
                'title' => $program->program,
                'category' => $program->category ?? '-',
                'type' => $program->type ?? '-',
                'price' => $program->price_note ?? '-',
                'status' => $schedule ? $schedule->ket : 'Tidak Aktif',
                'created_at' => $program->created_at
            ];
        });

        // Get pending program approvals for this instructor
        $pendingApprovals = DB::table('program_approvals')
            ->where('instructor_id', $trainerId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('instructor.programs.index', compact('programs', 'pendingApprovals'));
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
            'price_note' => 'nullable|string|max:255',
            'type' => 'required|in:online,offline,video',
            'course' => 'nullable|string|max:255',
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
        ]);

        // Get current instructor/trainer ID
        $trainerId = null;
        if (auth()->check()) {
            $user = auth()->user();
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();
            if ($trainer) {
                $trainerId = $trainer->id;
            }
        }
        
        if (!$trainerId) {
            $trainer = DB::table('data_trainers')
                ->where('status_trainer', 'Aktif')
                ->first();
            $trainerId = $trainer ? $trainer->id : null;
        }

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
        $approvalId = DB::table('program_approvals')->insertGetId([
            'instructor_id' => $trainerId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'type' => $validated['type'],
            'price_note' => $validated['price_note'] ?? null,
            'course' => $validated['course'] ?? null,
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
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('instructor.programs.index')
            ->with('success', 'Program berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get program by id
        return view('instructor.programs.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('instructor.programs.index')->with('success', 'Program berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('instructor.programs.index')->with('success', 'Program berhasil dihapus');
    }
}

