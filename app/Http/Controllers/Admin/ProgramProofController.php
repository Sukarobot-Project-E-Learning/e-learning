<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\CertificateController;
use App\Services\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('program_proofs')
            ->leftJoin('users', 'program_proofs.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->leftJoin('schedules', 'program_proofs.schedule_id', '=', 'schedules.id')
            ->leftJoin('certificate_templates', 'program_proofs.program_id', '=', 'certificate_templates.program_id')
            ->select(
                'program_proofs.id',
                'program_proofs.program_id',
                'program_proofs.student_id',
                'program_proofs.schedule_id',
                'program_proofs.documentation',
                'program_proofs.status',
                'program_proofs.created_at',
                'users.name as student_name',
                'data_programs.program as program_title',
                'data_programs.start_date as program_start_date',
                'data_programs.end_date as program_end_date',
                'schedules.tanggal_mulai as schedule_start_date',
                'schedules.tanggal_selesai as schedule_end_date',
                DB::raw('IF(certificate_templates.id IS NOT NULL, 1, 0) as has_certificate_template')
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'name', 'label' => 'Nama Peserta', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'program_title', 'label' => 'Program', 'sortable' => true],
                ['key' => 'schedule', 'label' => 'Jadwal'],
                ['key' => 'documentation', 'label' => 'Dokumentasi', 'type' => 'image'],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['users.name', 'data_programs.program'],
            'sortable' => ['student_name', 'program_title', 'status', 'created_at'],
            'sortColumns' => [
                'name' => 'users.name',
                'program_title' => 'data_programs.program',
                'status' => 'program_proofs.status',
                'created_at' => 'program_proofs.created_at',
            ],
            'actions' => ['view'],
            'route' => 'admin.program-proofs',
            'routeParam' => 'id',
            'title' => 'Manajemen Bukti Program',
            'entity' => 'bukti program',
            'showCreate' => false,
            'searchPlaceholder' => 'Cari nama peserta, program...',
            'filter' => [
                'key' => 'status',
                'column' => 'program_proofs.status',
                'options' => [
                    '' => 'Semua Status',
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ]
            ],
            'badgeClasses' => [
                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                'Disetujui' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            ],
            'transformer' => function($proof) {
                $statusMap = [
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ];

                return [
                    'id' => $proof->id,
                    'name' => $proof->student_name ?? 'N/A',
                    'program_title' => $proof->program_title ?? 'N/A',
                    'program_id' => $proof->program_id,
                    'student_id' => $proof->student_id,
                    'schedule' => $this->resolveProofScheduleText($proof),
                    'documentation' => $proof->documentation ? asset('storage/' . $proof->documentation) : null,
                    'status' => $statusMap[$proof->status] ?? $proof->status,
                    'status_raw' => $proof->status,
                    'created_at' => $proof->created_at,
                    'has_certificate_template' => $proof->has_certificate_template ?? 0
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.program-proofs.index', compact('data'));
    }

    /**
     * Resolve schedule text for proof table row with fallback order:
     * 1) Joined schedule dates from program_proofs.schedule_id
     * 2) Direct lookup by schedule_id (if selected join did not return)
     * 3) Latest schedule for related program (active preferred)
     * 4) Program start/end dates
     */
    private function resolveProofScheduleText($proof): string
    {
        if (!empty($proof->schedule_start_date)) {
            return $this->formatScheduleRange($proof->schedule_start_date, $proof->schedule_end_date ?? null);
        }

        if (!empty($proof->schedule_id)) {
            $scheduleById = DB::table('schedules')
                ->select('tanggal_mulai', 'tanggal_selesai')
                ->where('id', $proof->schedule_id)
                ->first();

            if ($scheduleById && !empty($scheduleById->tanggal_mulai)) {
                return $this->formatScheduleRange($scheduleById->tanggal_mulai, $scheduleById->tanggal_selesai ?? null);
            }
        }

        if (!empty($proof->program_id)) {
            $scheduleByProgram = DB::table('schedules')
                ->select('tanggal_mulai', 'tanggal_selesai')
                ->where('id_program', $proof->program_id)
                ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ket, ''))) = 'aktif' THEN 0 ELSE 1 END")
                ->orderByDesc('tanggal_mulai')
                ->first();

            if ($scheduleByProgram && !empty($scheduleByProgram->tanggal_mulai)) {
                return $this->formatScheduleRange($scheduleByProgram->tanggal_mulai, $scheduleByProgram->tanggal_selesai ?? null);
            }
        }

        return $this->formatScheduleRange($proof->program_start_date ?? null, $proof->program_end_date ?? null);
    }

    /**
     * Format schedule date range to display string.
     */
    private function formatScheduleRange($startDate, $endDate): string
    {
        if (empty($startDate)) {
            return '-';
        }

        $start = Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y');

        if (!empty($endDate) && $endDate !== $startDate) {
            return $start . ' - ' . Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y');
        }

        return $start;
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
     * Requires certificate template to be set for the program first.
     */
    public function accept(Request $request, $id)
    {
        try {
            // Get proof details
            $proof = DB::table('program_proofs')
                ->where('id', $id)
                ->first();

            if (!$proof) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Bukti program tidak ditemukan.'], 404);
                }
                return redirect()->route('admin.program-proofs.index')
                    ->with('error', 'Bukti program tidak ditemukan.');
            }

            // Check if certificate template exists for this program FIRST
            $template = CertificateController::getTemplateForProgram($proof->program_id);
            
            if (!$template) {
                // No template - return failure, don't change status
                $message = 'Gagal menerima bukti program. Template sertifikat belum disantumkan pada program ini. Silakan tambahkan template sertifikat terlebih dahulu.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->route('admin.program-proofs.index')
                    ->with('error', $message);
            }

            // Template exists, proceed to accept and generate certificate
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'accepted',
                    'accepted_by' => auth()->id(),
                    'accepted_at' => now(),
                    'updated_at' => now(),
                ]);

            // Auto-generate certificate for the user
            $result = CertificateController::generateCertificateForUser(
                $template->id,
                $proof->program_id,
                $proof->student_id,
                $id
            );

            if ($result['success']) {
                $message = 'Bukti program diterima dan sertifikat berhasil di-generate (No: ' . $result['certificate_number'] . ')';
            } else {
                // Certificate generation failed but proof is still accepted
                $message = 'Bukti program diterima, tetapi sertifikat gagal di-generate: ' . $result['message'];
            }

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'certificate_generated' => $result['success']
                ]);
            }

            return redirect()->route('admin.program-proofs.index')
                ->with($result['success'] ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.program-proofs.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject the program proof and delete it.
     */
    public function reject(Request $request, $id)
    {
        try {
            // Get proof details first
            $proof = DB::table('program_proofs')->where('id', $id)->first();
            
            if (!$proof) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Bukti program tidak ditemukan.'], 404);
                }
                return redirect()->route('admin.program-proofs.index')
                    ->with('error', 'Bukti program tidak ditemukan.');
            }

            // Delete documentation file from storage if exists
            if ($proof->documentation) {
                if (Storage::disk('public')->exists($proof->documentation)) {
                    Storage::disk('public')->delete($proof->documentation);
                } else {
                    // Fallback: try old public path for legacy files
                    $filePath = public_path($proof->documentation);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Delete the program proof record
            DB::table('program_proofs')->where('id', $id)->delete();

            $message = 'Bukti program berhasil ditolak dan dihapus';

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('admin.program-proofs.index')->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
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
                // Delete from storage
                if (Storage::disk('public')->exists($proof->documentation)) {
                    Storage::disk('public')->delete($proof->documentation);
                } else {
                    // Fallback: try old public path for legacy files
                    $filePath = public_path($proof->documentation);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            DB::table('program_proofs')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Bukti program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus bukti program'], 500);
        }
    }
}
