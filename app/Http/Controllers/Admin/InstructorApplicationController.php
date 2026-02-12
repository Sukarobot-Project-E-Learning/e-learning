<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
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
        $query = DB::table('instructor_applications')
            ->join('users', 'instructor_applications.user_id', '=', 'users.id')
            ->where('instructor_applications.status', 'pending')
            ->select(
                'instructor_applications.id',
                'instructor_applications.skills',
                'instructor_applications.status',
                'instructor_applications.created_at',
                'users.name',
                'users.email',
                'users.avatar'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'avatar', 'label' => 'Foto', 'type' => 'avatar'],
                ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ['key' => 'skills', 'label' => 'Keahlian', 'sortable' => true],
                ['key' => 'date', 'label' => 'Tanggal Pengajuan', 'sortable' => true, 'type' => 'date'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['users.name', 'users.email', 'instructor_applications.skills'],
            'sortable' => ['name', 'email', 'skills', 'created_at'],
            'sortColumns' => [
                'name' => 'users.name',
                'email' => 'users.email',
                'skills' => 'instructor_applications.skills',
                'created_at' => 'instructor_applications.created_at',
            ],
            'actions' => ['view'],
            'route' => 'admin.instructor-applications',
            'routeParam' => 'id',
            'title' => 'Pengajuan Instruktur',
            'entity' => 'pengajuan instruktur',
            'showCreate' => false,
            'showFilter' => false,
            'searchPlaceholder' => 'Cari nama, email, keahlian...',
            'defaultDir' => 'asc',
            'transformer' => function($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->name ?? 'N/A',
                    'email' => $application->email ?? '-',
                    'avatar' => $application->avatar,
                    'skills' => $application->skills ?? '-',
                    'status' => $application->status,
                    'date' => $application->created_at ? date('d F Y', strtotime($application->created_at)) : '-',
                    'created_at' => $application->created_at
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.instructor-applications.index', compact('data'));
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

        return view('admin.instructor-applications.show', compact('application'));
    }

    /**
     * Approve an application.
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $application = DB::table('instructor_applications')->where('id', $id)->first();
            if (!$application) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
                }
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
            
            $message = 'Pengajuan instruktur disetujui';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->route('admin.instructor-applications.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Reject an application.
     */
    public function reject(Request $request, $id)
    {
        try {
            $application = DB::table('instructor_applications')->where('id', $id)->first();
            if (!$application) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
                }
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan');
            }

            DB::table('instructor_applications')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
            
            $message = 'Pengajuan instruktur ditolak';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->route('admin.instructor-applications.index')->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
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
            return redirect()->back()->with('error', 'Pengajuan tidak ditemukan');
        }

        // Handle avatar separately (from users table)
        if ($type === 'avatar') {
            $user = DB::table('users')->where('id', $application->user_id)->first();
            if (!$user || empty($user->avatar)) {
                return redirect()->back()->with('error', 'Foto profil tidak ditemukan');
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
            
            return redirect()->back()->with('error', 'File foto tidak ditemukan');
        }

        $pathField = $type . '_path';
        $validTypes = ['cv', 'ktp', 'npwp'];
        
        if (!in_array($type, $validTypes) || empty($application->$pathField)) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan');
        }

        $path = $application->$pathField;
        
        // Check if file exists in local storage
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }
        
        // Try public storage as fallback
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }
        
        // Try public path as last resort
        if (file_exists(public_path($path))) {
            return response()->download(public_path($path));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }


}

