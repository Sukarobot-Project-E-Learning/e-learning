<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstructorApplicationController extends Controller
{
    /**
     * Display a listing of pending instructor applications.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['name', 'email', 'skills', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $query = DB::table('instructor_applications')
            ->join('users', 'instructor_applications.user_id', '=', 'users.id')
            ->where('instructor_applications.status', 'pending')
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('users.name', 'like', '%' . $s . '%')
                        ->orWhere('users.email', 'like', '%' . $s . '%')
                        ->orWhere('instructor_applications.skills', 'like', '%' . $s . '%');
                });
            })
            ->select('instructor_applications.*', 'users.name', 'users.email', 'users.avatar');

        $applications = $query->orderBy($sortKey === 'name' || $sortKey === 'email' ? 'users.' . $sortKey : 'instructor_applications.' . $sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($applications);
        }

        return view('admin.instructors.applications', compact('applications'));
    }

    /**
     * Approve an application.
     */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $application = DB::table('instructor_applications')
            ->join('users', 'instructor_applications.user_id', '=', 'users.id')
            ->where('instructor_applications.id', $id)
            ->select('instructor_applications.*', 'users.name', 'users.email', 'users.avatar', 'users.phone')
            ->first();

        if (!$application) {
            return redirect()->route('admin.instructor-applications.index')
                ->with('error', 'Pengajuan tidak ditemukan');
        }

        return view('admin.instructors.show', compact('application'));
    }

    /**
     * Approve an application.
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $application = DB::table('instructor_applications')->where('id', $id)->first();
            if (!$application) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan');
            }

            // Update application status
            DB::table('instructor_applications')->where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);

            // Update user role and status
            DB::table('users')->where('id', $application->user_id)->update([
                'role' => 'instructor',
                'is_active' => 1,
                'updated_at' => now()
            ]);

            // Get user data
            $user = DB::table('users')->where('id', $application->user_id)->first();

            // Create/Update data_trainers
            $trainerData = [
                'nama' => $user->name,
                'email' => $user->email,
                'telephone' => $user->phone,
                'keahlian' => $application->skills,
                'bio' => $application->bio,
                'status_trainer' => 'Aktif',
                'updated_at' => now(),
            ];

            $exists = DB::table('data_trainers')->where('email', $user->email)->exists();
            if ($exists) {
                DB::table('data_trainers')->where('email', $user->email)->update($trainerData);
            } else {
                $trainerData['created_at'] = now();
                DB::table('data_trainers')->insert($trainerData);
            }

            DB::commit();
            return redirect()->route('admin.instructor-applications.index')->with('success', 'Pengajuan instruktur disetujui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject an application.
     */
    public function reject($id)
    {
        DB::table('instructor_applications')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        return redirect()->route('admin.instructor-applications.index')->with('success', 'Pengajuan instruktur ditolak');
    }

    /**
     * Download private files (KTP, NPWP).
     */
    public function downloadFile($id, $type)
    {
        $application = DB::table('instructor_applications')->where('id', $id)->first();
        
        if (!$application) {
            return redirect()->back()->with('error', 'Pengajuan tidak ditemukan');
        }

        $filePath = null;
        $fileName = '';

        switch ($type) {
            case 'cv':
                $filePath = $application->cv_path;
                $fileName = 'CV_' . $id;
                break;
            case 'ktp':
                $filePath = $application->ktp_path;
                $fileName = 'KTP_' . $id;
                break;
            case 'npwp':
                $filePath = $application->npwp_path ?? null;
                $fileName = 'NPWP_' . $id;
                break;
            default:
                return redirect()->back()->with('error', 'Tipe file tidak valid');
        }

        if (!$filePath) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        // CV is stored in public storage
        if ($type === 'cv') {
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->download($filePath, $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
            }
        } else {
            // KTP and NPWP are stored in local (private) storage
            if (Storage::disk('local')->exists($filePath)) {
                return Storage::disk('local')->download($filePath, $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
            }
        }

        // Fallback: check if file exists in old public path
        if (file_exists(public_path($filePath))) {
            return response()->download(public_path($filePath), $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $application = DB::table('instructor_applications')->where('id', $id)->first();
            
            if ($application) {
                // Delete CV from public storage
                if ($application->cv_path) {
                    if (Storage::disk('public')->exists($application->cv_path)) {
                        Storage::disk('public')->delete($application->cv_path);
                    } elseif (file_exists(public_path($application->cv_path))) {
                        unlink(public_path($application->cv_path));
                    }
                }
                
                // Delete KTP from private storage
                if ($application->ktp_path) {
                    if (Storage::disk('local')->exists($application->ktp_path)) {
                        Storage::disk('local')->delete($application->ktp_path);
                    } elseif (file_exists(public_path($application->ktp_path))) {
                        unlink(public_path($application->ktp_path));
                    }
                }
                
                // Delete NPWP from private storage
                if (isset($application->npwp_path) && $application->npwp_path) {
                    if (Storage::disk('local')->exists($application->npwp_path)) {
                        Storage::disk('local')->delete($application->npwp_path);
                    }
                }
                
                DB::table('instructor_applications')->where('id', $id)->delete();
                return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dihapus']);
            }
            
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pengajuan'], 500);
        }
    }

    /**
     * Download private document (CV, KTP, NPWP, or Avatar) - Admin only
     */
    public function downloadDocument($id, $type)
    {
        $application = DB::table('instructor_applications')->where('id', $id)->first();
        
        if (!$application) {
            abort(404, 'Pengajuan tidak ditemukan');
        }

        // Handle avatar separately (from users table)
        if ($type === 'avatar') {
            $user = DB::table('users')->where('id', $application->user_id)->first();
            if (!$user || empty($user->avatar)) {
                abort(404, 'Foto profil tidak ditemukan');
            }
            
            $avatarPath = $user->avatar;
            
            // Check if it's a public storage path
            if (str_starts_with($avatarPath, 'avatars/') || str_starts_with($avatarPath, 'images/')) {
                $fullPath = storage_path('app/public/' . $avatarPath);
                if (file_exists($fullPath)) {
                    return response()->download($fullPath);
                }
            }
            
            // Try local storage
            if (Storage::disk('local')->exists($avatarPath)) {
                return Storage::disk('local')->download($avatarPath);
            }
            
            // Try public storage
            if (Storage::disk('public')->exists($avatarPath)) {
                return Storage::disk('public')->download($avatarPath);
            }
            
            abort(404, 'File foto tidak ditemukan');
        }

        $pathField = $type . '_path';
        $validTypes = ['cv', 'ktp', 'npwp'];
        
        if (!in_array($type, $validTypes) || empty($application->$pathField)) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        $path = $application->$pathField;
        
        // Check if file exists in storage
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('local')->download($path);
    }
}

