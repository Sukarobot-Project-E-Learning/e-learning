<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramApprovalController extends Controller
{
    /**
     * Display a listing of program approvals (pending programs from instructors)
     */
    public function index()
    {
        // Get pending program approvals with pagination (5 per page)
        $approvals = DB::table('program_approvals')
            ->leftJoin('data_trainers', 'program_approvals.instructor_id', '=', 'data_trainers.id')
            ->select(
                'program_approvals.*',
                'data_trainers.nama as instructor_name',
                'data_trainers.email as instructor_email'
            )
            ->orderBy('program_approvals.created_at', 'desc')
            ->paginate(5);

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
            ->where('status', 'pending')
            ->first();

        if (!$approval) {
            return redirect()->route('admin.program-approvals.index')
                ->with('error', 'Program approval tidak ditemukan atau sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Create program in data_programs table
            $programId = DB::table('data_programs')->insertGetId([
                'program' => $approval->title,
                'description' => $approval->description,
                'category' => $approval->category,
                'type' => $approval->type,
                'price' => 0, // Will be updated later if needed
                'price_note' => $approval->price_note,
                'image' => $approval->image,
                'status' => 'published',
                'instructor_id' => $approval->instructor_id,
                'start_date' => $approval->start_date,
                'end_date' => $approval->end_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update program_approvals status
            DB::table('program_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'program_id' => $programId,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
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
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $approval = DB::table('program_approvals')
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$approval) {
            return redirect()->route('admin.program-approvals.index')
                ->with('error', 'Program approval tidak ditemukan atau sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Update program_approvals status
            DB::table('program_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason,
                    'rejected_at' => now(),
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
}

