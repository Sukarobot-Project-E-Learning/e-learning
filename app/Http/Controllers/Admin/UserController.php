<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of users (role='user')
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['name', 'email', 'phone', 'is_active', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = \App\Models\User::query()
            ->where('role', 'user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('name', 'like', '%' . $s . '%')
                        ->orWhere('email', 'like', '%' . $s . '%')
                        ->orWhere('phone', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->input('status') === 'active' ? 1 : 0);
            })
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at');

        $users = $query->orderBy($sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

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
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string',
                'job' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = strtolower($validated['status'] ?? 'aktif') === 'aktif';

            $data = [
                'name' => $validated['name'],
                'username' => $validated['username'],
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

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'user_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('users/' . $photoName, $imageData);
                $data['avatar'] = 'users/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $data['avatar'] = $photo->storeAs('users', $photoName, 'public');
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
            'username' => $user->username ?? '',
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
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'nullable|string',
                'job' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = strtolower($validated['status'] ?? 'aktif') === 'aktif';

            $updateData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
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

            // Get old user data for photo deletion
            $oldUser = DB::table('users')->where('id', $id)->first();

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                // Delete old photo first
                if ($oldUser && $oldUser->avatar) {
                    if (Storage::disk('public')->exists($oldUser->avatar)) {
                        Storage::disk('public')->delete($oldUser->avatar);
                    } elseif (file_exists(public_path($oldUser->avatar))) {
                        unlink(public_path($oldUser->avatar));
                    }
                }

                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'user_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('users/' . $photoName, $imageData);
                $updateData['avatar'] = 'users/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                // Delete old photo first
                if ($oldUser && $oldUser->avatar) {
                    if (Storage::disk('public')->exists($oldUser->avatar)) {
                        Storage::disk('public')->delete($oldUser->avatar);
                    } elseif (file_exists(public_path($oldUser->avatar))) {
                        unlink(public_path($oldUser->avatar));
                    }
                }

                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $updateData['avatar'] = $photo->storeAs('users', $photoName, 'public');
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
            // Delete photo file if exists
            $user = DB::table('users')->where('id', $id)->first();
            if ($user && $user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of admins (role='admin')
     */
    public function indexAdmins(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['name', 'email', 'phone', 'is_active', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = DB::table('users')
            ->where('role', 'admin')
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('name', 'like', '%' . $s . '%')
                        ->orWhere('email', 'like', '%' . $s . '%')
                        ->orWhere('phone', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->input('status') === 'active' ? 1 : 0);
            })
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at');

        $users = $query->orderBy($sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

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
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = strtolower($validated['status'] ?? 'aktif') === 'aktif';

            $data = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
                'is_active' => $isActive,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'admin_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('admins/' . $photoName, $imageData);
                $data['avatar'] = 'admins/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $data['avatar'] = $photo->storeAs('admins', $photoName, 'public');
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
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = strtolower($validated['status'] ?? 'aktif') === 'aktif';

            $updateData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $isActive,
                'updated_at' => now(),
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }


            // Get old admin data for photo deletion
            $oldAdmin = DB::table('users')->where('id', $id)->first();

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                // Delete old photo first
                if ($oldAdmin && $oldAdmin->avatar) {
                    if (Storage::disk('public')->exists($oldAdmin->avatar)) {
                        Storage::disk('public')->delete($oldAdmin->avatar);
                    } elseif (file_exists(public_path($oldAdmin->avatar))) {
                        unlink(public_path($oldAdmin->avatar));
                    }
                }

                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'admin_' . time() . '_' . uniqid() . '.png';
                Storage::disk('public')->put('admins/' . $photoName, $imageData);
                $updateData['avatar'] = 'admins/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                // Delete old photo first
                if ($oldAdmin && $oldAdmin->avatar) {
                    if (Storage::disk('public')->exists($oldAdmin->avatar)) {
                        Storage::disk('public')->delete($oldAdmin->avatar);
                    } elseif (file_exists(public_path($oldAdmin->avatar))) {
                        unlink(public_path($oldAdmin->avatar));
                    }
                }
                
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $updateData['avatar'] = $photo->storeAs('admins', $photoName, 'public');
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
            // Delete photo file if exists
            $admin = DB::table('users')->where('id', $id)->first();
            if ($admin && $admin->avatar && file_exists(public_path($admin->avatar))) {
                unlink(public_path($admin->avatar));
            }
            
            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Admin berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus admin: ' . $e->getMessage()], 500);
        }
    }
}



