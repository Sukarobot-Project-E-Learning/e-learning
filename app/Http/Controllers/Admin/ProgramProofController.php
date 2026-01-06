<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CertificateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get program proofs from database with pagination (10 per page)
        $proofs = DB::table('program_proofs')
            ->leftJoin('users', 'program_proofs.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->leftJoin('schedules', 'program_proofs.schedule_id', '=', 'schedules.id')
            ->leftJoin('certificate_templates', 'program_proofs.program_id', '=', 'certificate_templates.program_id')
            ->select(
                'program_proofs.*',
                'users.name as student_name',
                'data_programs.program as program_title',
                DB::raw("CONCAT(DATE_FORMAT(schedules.tanggal_mulai, '%d %M %Y'), IF(schedules.tanggal_selesai IS NOT NULL AND schedules.tanggal_selesai != schedules.tanggal_mulai, CONCAT(' - ', DATE_FORMAT(schedules.tanggal_selesai, '%d %M %Y')), '')) as schedule"),
                DB::raw('IF(certificate_templates.id IS NOT NULL, 1, 0) as has_certificate_template')
            )
            ->orderBy('program_proofs.created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $proofs->getCollection()->transform(function($proof) {
            return [
                'id' => $proof->id,
                'name' => $proof->student_name ?? 'N/A',
                'program_title' => $proof->program_title ?? 'N/A',
                'program_id' => $proof->program_id,
                'student_id' => $proof->student_id,
                'schedule' => $proof->schedule ?? '-',
                'documentation' => $proof->documentation,
                'status' => $proof->status,
                'has_certificate_template' => $proof->has_certificate_template ?? 0
            ];
        });

        return view('admin.program-proofs.index', compact('proofs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proof = DB::table('program_proofs')
            ->leftJoin('users', 'program_proofs.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->leftJoin('schedules', 'program_proofs.schedule_id', '=', 'schedules.id')
            ->leftJoin('certificate_templates', 'program_proofs.program_id', '=', 'certificate_templates.program_id')
            ->select(
                'program_proofs.*',
                'users.name as user_name',
                'data_programs.program as program_title',
                'data_programs.type',
                'schedules.tanggal_mulai as start_date',
                'schedules.jam_mulai as start_time',
                'schedules.tanggal_selesai as end_date',
                'schedules.jam_selesai as end_time',
                'certificate_templates.id as template_id',
                DB::raw('IF(certificate_templates.id IS NOT NULL, 1, 0) as has_certificate_template')
            )
            ->where('program_proofs.id', $id)
            ->first();

        if (!$proof) {
            return redirect()->route('admin.program-proofs.index')
                ->with('error', 'Bukti program tidak ditemukan.');
        }

        return view('admin.program-proofs.show', compact('proof'));
    }

    /**
     * Accept the program proof and auto-generate certificate if template exists.
     */
    public function accept($id)
    {
        try {
            // Get proof details
            $proof = DB::table('program_proofs')
                ->where('id', $id)
                ->first();

            if (!$proof) {
                return redirect()->route('admin.program-proofs.index')
                    ->with('error', 'Bukti program tidak ditemukan.');
            }

            // Update proof status to accepted
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'accepted',
                    'accepted_by' => auth()->id(),
                    'accepted_at' => now(),
                    'updated_at' => now(),
                ]);

            $message = 'Bukti program berhasil diterima';
            $certificateGenerated = false;

            // Check if certificate template exists for this program
            $template = CertificateController::getTemplateForProgram($proof->program_id);
            
            if ($template) {
                // Auto-generate certificate for the user
                $result = CertificateController::generateCertificateForUser(
                    $template->id,
                    $proof->program_id,
                    $proof->student_id,
                    $id
                );

                if ($result['success']) {
                    $certificateGenerated = true;
                    $message = 'Bukti program diterima dan sertifikat berhasil di-generate (No: ' . $result['certificate_number'] . ')';
                } else {
                    // Certificate generation failed but proof is still accepted
                    $message = 'Bukti program diterima, tetapi sertifikat gagal di-generate: ' . $result['message'];
                }
            } else {
                $message = 'Bukti program diterima. (Catatan: Template sertifikat belum tersedia untuk program ini)';
            }

            return redirect()->route('admin.program-proofs.index')
                ->with($certificateGenerated ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.program-proofs.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject the program proof.
     */
    public function reject($id)
    {
        try {
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.program-proofs.index')->with('success', 'Bukti program berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->route('admin.program-proofs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $proof = DB::table('program_proofs')->where('id', $id)->first();
            if ($proof && $proof->documentation) {
                $filePath = public_path($proof->documentation);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            DB::table('program_proofs')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Bukti program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus bukti program'], 500);
        }
    }
}
