<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Get programs for current instructor
        // $programs = Program::where('instructor_id', auth()->id())->get();
        
        // Dummy data untuk sementara
        $programs = [
            [
                'id' => 1,
                'title' => 'Strategi Digital Marketing',
                'category' => 'Pelatihan',
                'start_date' => '08 October 2025',
                'type' => 'Online',
                'price' => 'Rp. 30.000',
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'title' => 'Workshop Branding',
                'category' => 'Training',
                'start_date' => '15 October 2025',
                'type' => 'Video',
                'price' => 'Rp. 50.000',
                'status' => 'Aktif'
            ],
        ];

        return view('instructor.programs.index', compact('programs'));
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
        // TODO: Add validation and store logic here
        // $validated = $request->validate([...]);
        
        // Store logic here
        return redirect()->route('instructor.programs.index')->with('success', 'Program berhasil ditambahkan');
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

