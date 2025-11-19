<?php

namespace App\Http\Controllers\Admin;

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
        // Get programs from database with pagination (5 per page)
        $programs = DB::table('data_programs')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $programs->getCollection()->transform(function($program) {
            // Get level count for this program
            $levelCount = DB::table('data_levels')
                ->where('id_programs', $program->id)
                ->count();

            // Get active schedule count
            $scheduleCount = DB::table('schedules')
                ->where('id_program', $program->id)
                ->where('ket', 'Aktif')
                ->count();

            return [
                'id' => $program->id,
                'title' => $program->program,
                'category' => '-',
                'start_date' => $program->created_at ? date('d F Y', strtotime($program->created_at)) : '-',
                'type' => '-',
                'price' => '-',
                'level_count' => $levelCount,
                'schedule_count' => $scheduleCount
            ];
        });

        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // $validated = $request->validate([...]);
        
        // Store logic here
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get program by id
        return view('admin.programs.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::table('data_programs')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus program'], 500);
        }
    }
}

