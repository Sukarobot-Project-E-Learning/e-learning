<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Controller methods for user-related actions can be added here
    public function profile(Request $request)
    {
        if ($request->has('cancel')) {
            session()->flash('error', 'Batal memperbarui profil.');
            return redirect()->route('client.dashboard');
        }

        // Logic to display user profile
        $user = Auth::user(); // ambil data user dari database
        
        // Check for instructor application
        $instructorApplication = DB::table('instructor_applications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Auto-update role if application is approved but role is not yet 'instructor'
        if ($instructorApplication && $instructorApplication->status == 'approved' && $user->role !== 'instructor') {
            DB::table('users')->where('id', $user->id)->update(['role' => 'instructor']);
            $user->role = 'instructor'; // Update instance for view
        }

        return view('client.dashboard.profile', compact('user', 'instructorApplication'));
    }

    public function program()
    {
        $user = Auth::user();
        
        // Get enrolled programs for the user
        $enrollments = DB::table('enrollments')
            ->join('data_programs', 'enrollments.program_id', '=', 'data_programs.id')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->leftJoin('program_proofs', function($join) use ($user) {
                $join->on('enrollments.student_id', '=', 'program_proofs.student_id')
                     ->on('enrollments.program_id', '=', 'program_proofs.program_id');
            })
            ->select(
                'enrollments.*',
                'data_programs.program as program_name',
                'data_programs.slug',
                'data_programs.image',
                'data_programs.category',
                'data_programs.type',
                'data_programs.start_date',
                'data_programs.end_date',
                'users.name as instructor_name',
                'program_proofs.status as proof_status',
                'program_proofs.id as proof_id'
            )
            ->where('enrollments.student_id', $user->id)
            ->orderBy('enrollments.created_at', 'desc')
            ->get();

        return view('client.dashboard.program', compact('enrollments'));
    }

    public function certificate()
    {
        return view('client.dashboard.certificate');
    }

    public function transaction()
    {
        $user = Auth::user();

        // Get all transactions for the user with program details
        $transactions = Transaction::where('student_id', $user->id)
            ->with(['program' => function($query) {
                $query->select('id', 'program', 'slug', 'image', 'price');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark expired pending transactions
        foreach ($transactions as $transaction) {
            if ($transaction->status === 'pending' && $transaction->isExpired()) {
                $transaction->status = 'expired';
                $transaction->save();
            }
        }

        // Reload to get updated statuses
        $transactions = Transaction::where('student_id', $user->id)
            ->with(['program' => function($query) {
                $query->select('id', 'program', 'slug', 'image', 'price');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.dashboard.transaction', compact('transactions'));
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
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|min:10|max:20',
            'job' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|required_with:current_password',
            'new_password_confirmation' => 'nullable|same:new_password',
        ], [
            // Custom error massage
            'name.required' => 'Nama wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'job.max' => 'Pekerjaan maksimal 100 karakter',
            'address.max' => 'Alamat maksimal 255 karakter',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter',
            'new_password_confirmation.same' => 'Konfirmasi kata sandi tidak cocok',
            'current_password.required_with' => 'Kata sandi lama wajib diisi jika ingin mengubah kata sandi',
            'avatar.max' => 'Ukuran gambar maksimal 2048 KB',
        ]);

        // Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->job = $validated['job'];
        $user->address = $validated['address'];
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('assets/elearning/client/img/avatar'), $avatarName);
            $user->avatar = $avatarName;
        }

        // Update password
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi lama salah.'])->withInput();
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
