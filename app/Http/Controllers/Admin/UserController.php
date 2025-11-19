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
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string',
                'job' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'is_active' => $isActive,
                'job' => $validated['job'] ?? null,
                'address' => $validated['address'] ?? null,
                'country' => $validated['country'] ?? 'Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Upload photo jika ada (opsional)
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $uploadPath = public_path('uploads/users');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $photo->move($uploadPath, $photoName);
                $data['avatar'] = 'uploads/users/' . $photoName;
            }

            DB::table('users')->insert($data);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User tidak ditemukan');
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'status' => $user->is_active ? 'Aktif' : 'Non-Aktif',
            'job' => $user->job ?? '',
            'address' => $user->address ?? '',
            'country' => $user->country ?? 'Indonesia',
            'photo' => $user->avatar ?? null,
        ];

        return view('admin.users.edit', ['user' => (object)$userData]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'nullable|string',
                'job' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $isActive,
                'job' => $validated['job'] ?? null,
                'address' => $validated['address'] ?? null,
                'country' => $validated['country'] ?? 'Indonesia',
                'updated_at' => now(),
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Upload photo jika ada (opsional)
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $uploadPath = public_path('uploads/users');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $photo->move($uploadPath, $photoName);
                $updateData['avatar'] = 'uploads/users/' . $photoName;
            }

            DB::table('users')->where('id', $id)->update($updateData);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
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
     * Show the form for creating a new admin.
     */
    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin
     */
    public function storeAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'aktif') === 'aktif';

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
                'is_active' => $isActive,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Upload photo jika ada (opsional)
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $uploadPath = public_path('uploads/admins');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $photo->move($uploadPath, $photoName);
                $data['avatar'] = 'uploads/admins/' . $photoName;
            }

            DB::table('users')->insert($data);

            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function editAdmin($id)
    {
        $admin = DB::table('users')->where('id', $id)->first();
        
        if (!$admin) {
            return redirect()->route('admin.admins.index')->with('error', 'Admin tidak ditemukan');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin
     */
    public function updateAdmin(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'aktif') === 'aktif';

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $isActive,
                'updated_at' => now(),
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Upload photo jika ada (opsional)
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $uploadPath = public_path('uploads/admins');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $photo->move($uploadPath, $photoName);
                $updateData['avatar'] = 'uploads/admins/' . $photoName;
            }

            DB::table('users')->where('id', $id)->update($updateData);

            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate admin: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified admin
     */
    public function destroyAdmin($id)
    {
        try {
            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Admin berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus admin: ' . $e->getMessage()], 500);
        }
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
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan instructor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified instructor
     */
    public function updateInstructor(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengupdate instructor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified instructor
     */
    public function destroyInstructor($id)
    {
        try {
            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Instructor berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus instructor: ' . $e->getMessage()], 500);
        }
    }
}

