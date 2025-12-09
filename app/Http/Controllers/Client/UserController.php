<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Controller methods for user-related actions can be added here
    public function profile()
    {
        // Logic to display user profile
        $user = Auth::user(); // ambil data user dari database
        return view('client.dashboard.profile', compact('user'));
    }

    public function program()
    {
        return view('client.dashboard.program');
    }

    public function certificate()
    {
        return view('client.dashboard.certificate');
    }

    public function transaction()
    {
        return view('client.dashboard.transaction');
    }

    public function voucher()
    {
        return view('client.dashboard.voucher');
    }

    public function updateProfile(Request $request)
    {
        // Logic to update user profile
        $user = Auth::user();
        
        // Validasi
        $validate = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|min:10|max:20',
            'job' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Custom error massage
            'name.required' => 'Nama wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'job.max' => 'Pekerjaan maksimal 100 karakter',
            'address.max' => 'Alamat maksimal 255 karakter',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'avatar.max' => 'Ukuran gambar maksimal 2048 KB',
        ]);

        // Update data user
        $user->name = $validate['name'];
        $user->email = $validate['email'];
        $user->phone = $validate['phone'];
        $user->job = $validate['job'];
        $user->address = $validate['address'];
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('assets/elearning/client/img/avatar'), $avatarName);
            $user->avatar = $avatarName;
        }

        // Update password
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->with('error', 'Password lama salah.');
            }
            $user->password = Hash::make($validated['new_password']);
        }

        // Update foto
        if ($request->hasFile('avatar')) {
            // Hapus foto lama
            if ($user->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $user->avatar))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->avatar));
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = 'storage/'.$path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
