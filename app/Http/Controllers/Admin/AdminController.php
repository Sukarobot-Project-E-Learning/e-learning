<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $admins = [
            [
                'id' => 1,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 4,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 5,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 6,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 7,
                'name' => 'Nama Admin',
                'email' => 'emailadmin@gmail.com',
                'phone' => '081234567891',
                'photo' => null,
                'status' => 'Aktif'
            ],
        ];

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // $validated = $request->validate([...]);
        
        // Store logic here
        return redirect()->route('elearning.admin.admins.index')->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get admin by id
        return view('admin.admins.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('elearning.admin.admins.index')->with('success', 'Admin berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('elearning.admin.admins.index')->with('success', 'Admin berhasil dihapus');
    }
}

