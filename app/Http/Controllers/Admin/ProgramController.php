<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $programs = [
            [
                'id' => 1,
                'title' => 'Strategi Digital Marketing',
                'category' => 'Pelatihan',
                'start_date' => '08 October 2025',
                'type' => 'Online',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 2,
                'title' => 'Strategi Digital Marketing',
                'category' => 'Pelatihan',
                'start_date' => '08 October 2025',
                'type' => 'Online',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 3,
                'title' => 'Strategi Digital Marketing',
                'category' => 'Pelatihan',
                'start_date' => '08 October 2025',
                'type' => 'Online',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 4,
                'title' => 'Workshop Branding',
                'category' => 'Training',
                'start_date' => '08 October 2025',
                'type' => 'Video',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 5,
                'title' => 'Workshop Branding',
                'category' => 'Training',
                'start_date' => '08 October 2025',
                'type' => 'Video',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 6,
                'title' => 'New Level Digital Skill',
                'category' => 'Sertifikasi',
                'start_date' => '08 October 2025',
                'type' => 'Offline',
                'price' => 'Rp. 30.000'
            ],
            [
                'id' => 7,
                'title' => 'New Level Digital Skill',
                'category' => 'Sertifikasi',
                'start_date' => '08 October 2025',
                'type' => 'Offline',
                'price' => 'Rp. 30.000'
            ],
        ];

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
        return redirect()->route('elearning.admin.programs.index')->with('success', 'Program berhasil ditambahkan');
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
        return redirect()->route('elearning.admin.programs.index')->with('success', 'Program berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('elearning.admin.programs.index')->with('success', 'Program berhasil dihapus');
    }
}

