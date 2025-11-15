<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get users from database (combine users table with data_siswas for students)
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '-',
                    'photo' => null,
                    'status' => 'Aktif',
                    'role' => $user->role
                ];
            });

        // Also get students from data_siswas
        $students = DB::table('data_siswas')
            ->where('status_siswa', 'Aktif')
            ->select('id', 'nama_lengkap as name', 'telephone as phone', 'file as photo', 'status_siswa as status')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name ?? 'N/A',
                    'email' => '-',
                    'phone' => $student->phone ?? '-',
                    'photo' => $student->photo,
                    'status' => $student->status ?? 'Aktif',
                    'role' => 'student'
                ];
            });

        // Combine all users
        $allUsers = $users->merge($students);
        
        // Manual pagination for combined collection
        $currentPage = request()->get('page', 1);
        $perPage = 5;
        $total = $allUsers->count();
        $items = $allUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $allUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.users.index', compact('allUsers'));
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

