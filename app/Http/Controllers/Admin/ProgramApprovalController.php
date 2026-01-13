<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramApprovalController extends Controller
{
    /**
     * Display a listing of program approvals (pending programs from instructors)
     */
    public function index()
    {
        // Get all program approvals from database
        $approvals = DB::table('program_approvals')
            ->leftJoin('data_trainers', 'program_approvals.instructor_id', '=', 'data_trainers.id')
            ->select(
                'program_approvals.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.email as instructor_email'
            )
            ->orderBy('program_approvals.created_at', 'desc')
            ->get();

        return view('admin.program-approvals.index', compact('approvals'));
    }

    /**
     * Display the specified program approval
     */
    public function show($id)
    {
        $approval = DB::table('program_approvals')
            ->leftJoin('data_trainers', 'program_approvals.instructor_id', '=', 'data_trainers.id')
            ->leftJoin('users', 'program_approvals.approved_by', '=', 'users.id')
            ->select(
                'program_approvals.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.email as instructor_email',
                'data_trainers.telephone as instructor_phone',
                'users.name as approved_by_name'
            )
            ->where('program_approvals.id', $id)
            ->first();

        if (!$approval) {
            return redirect()->route('admin.program-approvals.index')
                ->with('error', 'Program approval tidak ditemukan.');
        }

        return view('admin.program-approvals.show', compact('approval'));
    }

    /**
     * Approve the program approval
     */
    public function approve(Request $request, $id)
    {
        $approval = DB::table('program_approvals')
            ->where('id', $id)
            ->first();

        if (!$approval) {
            return redirect()->route('admin.program-approvals.index')
                ->with('error', 'Program approval tidak ditemukan.');
        }

        // Skip if already approved
        if ($approval->status === 'approved') {
            return redirect()->route('admin.program-approvals.index')
                ->with('info', 'Program sudah disetujui sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $programId = $approval->program_id;

            // Create program in data_programs table if not already created
            if (!$programId) {
                // Generate unique slug
                $slug = Str::slug($approval->title);
                $originalSlug = $slug;
                $count = 1;
                while (DB::table('data_programs')->where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }

                $programId = DB::table('data_programs')->insertGetId([
                    'program' => $approval->title,
                    'slug' => $slug,
                    'description' => $approval->description,
                    'category' => $approval->category,
                    'type' => $approval->type,
                    'price' => $approval->price ?? 0,
                    'quota' => $approval->available_slots ?? 0,
                    'enrolled_count' => 0,
                    'image' => $approval->image,
                    'status' => 'published',
                    'instructor_id' => $approval->instructor_id,
                    'province' => $approval->province,
                    'city' => $approval->city,
                    'district' => $approval->district,
                    'village' => $approval->village,
                    'full_address' => $approval->full_address,
                    'start_date' => $approval->start_date,
                    'start_time' => $approval->start_time,
                    'end_date' => $approval->end_date,
                    'end_time' => $approval->end_time,
                    'tools' => $approval->tools,
                    'learning_materials' => $approval->materials,
                    'benefits' => $approval->benefits,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Update existing program status to published
                DB::table('data_programs')
                    ->where('id', $programId)
                    ->update([
                        'status' => 'published',
                        'updated_at' => now(),
                    ]);
            }

            // Update program_approvals status
            DB::table('program_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'program_id' => $programId,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'rejection_reason' => null,
                    'rejected_at' => null,
                    'updated_at' => now(),
                ]);

            // Create notification for instructor
            $instructor = DB::table('data_trainers')->where('id', $approval->instructor_id)->first();
            if ($instructor) {
                $user = DB::table('users')->where('email', $instructor->email)->first();
                if ($user) {
                    DB::table('notifications')->insert([
                        'user_id' => $user->id,
                        'type' => 'program_approved',
                        'title' => 'Program Disetujui',
                        'message' => "Program '{$approval->title}' telah disetujui oleh admin dan sudah dipublikasikan.",
                        'link' => route('instructor.programs.index'),
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.program-approvals.index')
                ->with('success', 'Program berhasil disetujui dan dipublikasikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.program-approvals.show', $id)
                ->with('error', 'Terjadi kesalahan saat menyetujui program: ' . $e->getMessage());
        }
    }

    /**
     * Reject the program approval
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:15|max:1000',
        ], [
            'rejection_reason.min' => 'Alasan penolakan minimal 15 karakter.',
        ]);

        $approval = DB::table('program_approvals')
            ->where('id', $id)
            ->first();

        if (!$approval) {
            return redirect()->route('admin.program-approvals.index')
                ->with('error', 'Program approval tidak ditemukan.');
        }

        // Skip if already rejected
        if ($approval->status === 'rejected') {
            return redirect()->route('admin.program-approvals.index')
                ->with('info', 'Program sudah ditolak sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Delete program if it was created
            if ($approval->program_id) {
                DB::table('data_programs')
                    ->where('id', $approval->program_id)
                    ->delete();
            }

            // Update program_approvals status
            DB::table('program_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'program_id' => null,
                    'rejection_reason' => $request->rejection_reason,
                    'rejected_at' => now(),
                    'approved_by' => null,
                    'approved_at' => null,
                    'updated_at' => now(),
                ]);

            // Create notification for instructor
            $instructor = DB::table('data_trainers')->where('id', $approval->instructor_id)->first();
            if ($instructor) {
                $user = DB::table('users')->where('email', $instructor->email)->first();
                if ($user) {
                    DB::table('notifications')->insert([
                        'user_id' => $user->id,
                        'type' => 'program_rejected',
                        'title' => 'Program Ditolak',
                        'message' => "Program '{$approval->title}' ditolak. Alasan: {$request->rejection_reason}",
                        'link' => route('instructor.programs.index'),
                        'is_read' => 0,
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.program-approvals.index')
                ->with('success', 'Program berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.program-approvals.show', $id)
                ->with('error', 'Terjadi kesalahan saat menolak program: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update status for multiple program approvals
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:program_approvals,id',
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|min:15|max:1000',
        ], [
            'rejection_reason.min' => 'Alasan penolakan minimal 15 karakter.',
        ]);

        $ids = $request->ids;
        $status = $request->status;
        $rejectionReason = $request->rejection_reason;

        DB::beginTransaction();
        try {
            $successCount = 0;
            $failCount = 0;

            foreach ($ids as $id) {
                $approval = DB::table('program_approvals')->where('id', $id)->first();
                
                if (!$approval) {
                    $failCount++;
                    continue;
                }

                // Skip if already has the same status
                if ($approval->status === $status) {
                    continue;
                }

                if ($status === 'approved') {
                    $programId = $approval->program_id;

                    // Create program in data_programs table if not already created
                    if (!$programId) {
                        // Generate unique slug
                        $slug = Str::slug($approval->title);
                        $originalSlug = $slug;
                        $count = 1;
                        while (DB::table('data_programs')->where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $count;
                            $count++;
                        }

                        $programId = DB::table('data_programs')->insertGetId([
                            'program' => $approval->title,
                            'slug' => $slug,
                            'description' => $approval->description,
                            'category' => $approval->category,
                            'type' => $approval->type,
                            'price' => $approval->price ?? 0,
                            'quota' => $approval->available_slots ?? 0,
                            'enrolled_count' => 0,
                            'image' => $approval->image,
                            'status' => 'published',
                            'instructor_id' => $approval->instructor_id,
                            'province' => $approval->province,
                            'city' => $approval->city,
                            'district' => $approval->district,
                            'village' => $approval->village,
                            'full_address' => $approval->full_address,
                            'start_date' => $approval->start_date,
                            'start_time' => $approval->start_time,
                            'end_date' => $approval->end_date,
                            'end_time' => $approval->end_time,
                            'tools' => $approval->tools,
                            'learning_materials' => $approval->materials,
                            'benefits' => $approval->benefits,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Update existing program status to published
                        DB::table('data_programs')
                            ->where('id', $programId)
                            ->update([
                                'status' => 'published',
                                'updated_at' => now(),
                            ]);
                    }

                    DB::table('program_approvals')
                        ->where('id', $id)
                        ->update([
                            'status' => 'approved',
                            'program_id' => $programId,
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                            'rejection_reason' => null,
                            'rejected_at' => null,
                            'updated_at' => now(),
                        ]);

                    // Create notification for instructor
                    $this->sendNotification($approval, 'program_approved', 'Program Disetujui', 
                        "Program '{$approval->title}' telah disetujui oleh admin dan sudah dipublikasikan.");

                    $successCount++;

                } elseif ($status === 'rejected') {
                    // Delete program if it was created
                    if ($approval->program_id) {
                        DB::table('data_programs')
                            ->where('id', $approval->program_id)
                            ->delete();
                    }

                    DB::table('program_approvals')
                        ->where('id', $id)
                        ->update([
                            'status' => 'rejected',
                            'program_id' => null,
                            'rejection_reason' => $rejectionReason,
                            'rejected_at' => now(),
                            'approved_by' => null,
                            'approved_at' => null,
                            'updated_at' => now(),
                        ]);

                    // Create notification for instructor
                    $this->sendNotification($approval, 'program_rejected', 'Program Ditolak', 
                        "Program '{$approval->title}' ditolak. Alasan: {$rejectionReason}");

                    $successCount++;
                }
            }

            DB::commit();

            $statusLabel = match($status) {
                'approved' => 'disetujui',
                'rejected' => 'ditolak',
            };

            return response()->json([
                'success' => true,
                'message' => "{$successCount} program berhasil {$statusLabel}.",
                'success_count' => $successCount,
                'fail_count' => $failCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to send notification to instructor
     */
    private function sendNotification($approval, $type, $title, $message)
    {
        $instructor = DB::table('data_trainers')->where('id', $approval->instructor_id)->first();
        if ($instructor) {
            $user = DB::table('users')->where('email', $instructor->email)->first();
            if ($user) {
                DB::table('notifications')->insert([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'link' => route('instructor.programs.index'),
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
            }
        }
    }
}

