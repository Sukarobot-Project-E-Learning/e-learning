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
use App\Http\Controllers\Admin\CertificateController;

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
        $user = Auth::user();

        // Get approved program proofs for this user
        $proofs = DB::table('program_proofs')
            ->join('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->select(
                'program_proofs.id as proof_id',
                'program_proofs.program_id',
                'program_proofs.student_id',
                'program_proofs.updated_at as approved_at',
                'data_programs.program as program_name'
            )
            ->where('program_proofs.student_id', $user->id)
            ->where('program_proofs.status', 'accepted')
            ->get();

        $certificates = [];

        foreach ($proofs as $proof) {
            // Check if certificate exists
            $certificate = DB::table('certificates')
                ->where('user_id', $user->id)
                ->where('program_id', $proof->program_id)
                ->first();

            // If not exists, try to generate it (Lazy Generation)
            if (!$certificate) {
                // Check if template exists
                if (CertificateController::hasTemplateForProgram($proof->program_id)) {
                    $template = CertificateController::getTemplateForProgram($proof->program_id);
                    
                    // Generate
                    $result = CertificateController::generateCertificateForUser(
                        $template->id,
                        $proof->program_id,
                        $user->id,
                        $proof->proof_id
                    );

                    if ($result['success']) {
                        // Fetch newly generated certificate
                        $certificate = DB::table('certificates')
                            ->where('id', $result['certificate_id'])
                            ->first();
                    }
                }
            }

            // If we have a certificate (existing or newly generated)
            if ($certificate) {
                $certificates[] = (object) [
                    'id' => $certificate->id,
                    'program_name' => $proof->program_name,
                    'issued_at' => is_string($certificate->issued_at) ? \Carbon\Carbon::parse($certificate->issued_at) : $certificate->issued_at,
                    'certificate_number' => $certificate->certificate_number
                ];
            }
        }

        return view('client.dashboard.certificate', compact('certificates'));
    }

    public function downloadCertificate($id)
    {
        $user = Auth::user();
        
        $certificate = DB::table('certificates')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$certificate) {
            return back()->with('error', 'Sertifikat tidak ditemukan.');
        }

        try {
            // Get file path
            // stored path is like "storage/certificates/generated/filename.png"
            // or relative path depending on how it was saved.
            // Based on CertificateController::renderCertificateImage:
            // return 'storage/certificates/generated/' . $fileName;
            
            // We need absolute path for file_get_contents
            // If it starts with storage/, we assume it's in public/storage linked to storage/app/public
            
            $path = $certificate->certificate_file;
            $localPath = '';

            if (str_starts_with($path, 'storage/')) {
                // It is in public/storage
                $localPath = public_path($path);
            } else {
                // Assume relative to storage/app/public ?
                $localPath = storage_path('app/public/' . $path);
            }

            if (!file_exists($localPath)) {
                // Try fallback logic
                if (file_exists(public_path($path))) {
                    $localPath = public_path($path);
                } else {
                    return back()->with('error', 'File sertifikat fisik tidak ditemukan.');
                }
            }

            $imageContent = file_get_contents($localPath);
            $base64Image = base64_encode($imageContent);
            
            // Detect mime type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContent);

            // Create HTML for PDF
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    @page { margin: 0; size: A4 landscape; }
                    body { margin: 0; padding: 0; }
                    img { width: 100%; height: 100%; object-fit: contain; display: block; }
                </style>
            </head>
            <body>
                <img src="data:' . $mimeType . ';base64,' . $base64Image . '" />
            </body>
            </html>';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('a4', 'landscape');

            $filename = 'Sertifikat_' . str_replace(' ', '_', $user->name) . '_' . date('Ymd') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh sertifikat: ' . $e->getMessage());
        }
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
        
        // Check if user is SSO (Google) user
        $isSsoUser = ($user->provider === 'google');
        
        // Build validation rules
        $rules = [
            'name' => 'required|string|min:3|max:255',
            'username' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],

            'phone' => 'nullable|string|min:10|max:20',
            'job' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_password' => 'nullable|min:8',
            'new_password_confirmation' => 'nullable|same:new_password',
        ];
        
        // For non-SSO users, require current password to change password
        if (!$isSsoUser) {
            $rules['current_password'] = 'nullable|required_with:new_password';
        }
        
        // Validasi
        $validated = $request->validate($rules, [
            // Custom error massage
            'name.required' => 'Nama wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore',

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
        $user->username = $validated['username'] ?? $user->username;

        $user->phone = $validated['phone'];
        $user->job = $validated['job'];
        $user->address = $validated['address'];
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            
            // Delete old avatar from storage
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            } elseif ($user->avatar && file_exists(public_path($user->avatar))) {
                // Fallback: delete from old public path
                unlink(public_path($user->avatar));
            }
            
            // Store new avatar to storage/app/public/users/
            $avatarPath = $avatar->storeAs('users', $avatarName, 'public');
            $user->avatar = $avatarPath;
        }

        // Update password
        if (!empty($validated['new_password'])) {
            // For SSO users, no need to check current password
            if ($isSsoUser) {
                $user->password = Hash::make($validated['new_password']);
            } else {
                // For non-SSO users, verify current password
                if (!Hash::check($validated['current_password'], $user->password)) {
                    return back()->with('error', 'Kata sandi saat ini salah.')->withInput();
                }
                $user->password = Hash::make($validated['new_password']);
            }
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Pilih gambar terlebih dahulu',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            
            // Delete old avatar from storage
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            } elseif ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            $avatarPath = $avatar->storeAs('users', $avatarName, 'public');
            $user->avatar = $avatarPath;
            $user->save();

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal mengupload gambar.');
    }

    /**
     * Check if username is available (AJAX endpoint)
     */
    public function checkUsername(Request $request)
    {
        $username = $request->get('username');
        $userId = Auth::id();
        
        if (empty($username)) {
            return response()->json(['available' => true]);
        }
        
        // Check if username exists (excluding current user)
        $exists = DB::table('users')
            ->where('username', $username)
            ->where('id', '!=', $userId)
            ->exists();
        
        return response()->json(['available' => !$exists]);
    }
}

