<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorApplicationController extends Controller
{
    /**
     * Display a listing of pending instructor applications.
     */
    public function index()
    {
        $applications = DB::table('instructor_applications')
            ->join('users', 'instructor_applications.user_id', '=', 'users.id')
            ->where('instructor_applications.status', 'pending')
            ->select('instructor_applications.*', 'users.name', 'users.email', 'users.avatar')
            ->orderBy('instructor_applications.created_at', 'asc')
            ->paginate(10);

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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $application = DB::table('instructor_applications')->where('id', $id)->first();
            
            if ($application) {
                // Determine if we should delete associated files
                // Typically you might want to keep them for records if rejected, but for destroy we usually delete
                if ($application->cv_path && file_exists(public_path($application->cv_path))) {
                    unlink(public_path($application->cv_path));
                }
                if ($application->ktp_path && file_exists(public_path($application->ktp_path))) {
                    unlink(public_path($application->ktp_path));
                }
                
                DB::table('instructor_applications')->where('id', $id)->delete();
                return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dihapus']);
            }
            
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan'], 404);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pengajuan'], 500);
        }
    }
}
