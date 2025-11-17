<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users (role='user')
     */
    public function index()
    {
        $users = DB::table('users')
            ->where('role', 'user')
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'message' => 'User berhasil diupdate']);
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    /**
     * Display a listing of admins (role='admin')
     */
    public function indexAdmins()
    {
        $users = DB::table('users')
            ->where('role', 'admin')
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.admins.index', compact('users'));
    }

    /**
     * Store a newly created admin
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Admin berhasil ditambahkan']);
    }

    /**
     * Update the specified admin
     */
    public function updateAdmin(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'message' => 'Admin berhasil diupdate']);
    }

    /**
     * Remove the specified admin
     */
    public function destroyAdmin($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Admin berhasil dihapus']);
    }

    /**
     * Display a listing of instructors (role='instructor')
     */
    public function indexInstructors()
    {
        $users = DB::table('users')
            ->where('role', 'instructor')
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.instructors_list.index', compact('users'));
    }

    /**
     * Store a newly created instructor
     */
    public function storeInstructor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Instructor berhasil ditambahkan']);
    }

    /**
     * Update the specified instructor
     */
    public function updateInstructor(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return response()->json(['success' => true, 'message' => 'Instructor berhasil diupdate']);
    }

    /**
     * Remove the specified instructor
     */
    public function destroyInstructor($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Instructor berhasil dihapus']);
    }
}

