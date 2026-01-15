<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = DB::table('users')
            ->where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

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

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            $uploadPath = public_path('images/admins');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'admin_' . time() . '_' . uniqid() . '.png';
                file_put_contents($uploadPath . '/' . $photoName, $imageData);
                $data['avatar'] = 'images/admins/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move($uploadPath, $photoName);
                $data['avatar'] = 'images/admins/' . $photoName;
            }

            DB::table('users')->insert($data);

            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admin = DB::table('users')->where('id', $id)->where('role', 'admin')->first();
        if (!$admin) {
            return redirect()->route('admin.admins.index')->with('error', 'Admin tidak ditemukan');
        }
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
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
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

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

            // Get old admin data for photo deletion
            $oldAdmin = DB::table('users')->where('id', $id)->first();

            $uploadPath = public_path('images/admins');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Upload photo - prioritize cropped_photo (base64) over regular file upload
            if ($request->filled('cropped_photo') && preg_match('/^data:image\/(\w+);base64,/', $request->input('cropped_photo'))) {
                // Delete old photo first
                if ($oldAdmin && $oldAdmin->avatar && file_exists(public_path($oldAdmin->avatar))) {
                    unlink(public_path($oldAdmin->avatar));
                }

                $base64Image = $request->input('cropped_photo');
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
                $imageData = base64_decode($imageData);
                $photoName = 'admin_' . time() . '_' . uniqid() . '.png';
                file_put_contents($uploadPath . '/' . $photoName, $imageData);
                $updateData['avatar'] = 'images/admins/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                // Delete old photo first
                if ($oldAdmin && $oldAdmin->avatar && file_exists(public_path($oldAdmin->avatar))) {
                    unlink(public_path($oldAdmin->avatar));
                }

                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move($uploadPath, $photoName);
                $updateData['avatar'] = 'images/admins/' . $photoName;
            }

            DB::table('users')->where('id', $id)->update($updateData);

            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate admin: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $admin = DB::table('users')->where('id', $id)->where('role', 'admin')->first();
            if ($admin && $admin->avatar && file_exists(public_path($admin->avatar))) {
                unlink(public_path($admin->avatar));
            }
            DB::table('users')->where('id', $id)->delete();
            return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus admin: ' . $e->getMessage());
        }
    }
}
