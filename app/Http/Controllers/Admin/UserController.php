<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $users = [
            [
                'id' => 1,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 4,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 5,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 6,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 7,
                'name' => 'Nama user',
                'email' => 'emailuser@gmail.com',
                'phone' => '081234567890',
                'photo' => null,
                'status' => 'Aktif'
            ],
        ];

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // $validated = $request->validate([...]);
        
        // Store logic here
        return redirect()->route('elearning.admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get user by id
        return view('admin.users.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('elearning.admin.users.index')->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('elearning.admin.users.index')->with('success', 'User berhasil dihapus');
    }
}

