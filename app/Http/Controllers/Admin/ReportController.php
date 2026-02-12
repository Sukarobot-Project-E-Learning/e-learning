<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.program as title',
                'data_programs.created_at'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Program', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'schedule', 'label' => 'Jadwal'],
                ['key' => 'total_certified_users', 'label' => 'Peserta Bersertifikat'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['data_programs.program'],
            'sortable' => ['title', 'created_at'],
            'sortColumns' => [
                'title' => 'data_programs.program',
                'created_at' => 'data_programs.created_at',
            ],
            'actions' => [],
            'route' => 'admin.reports',
            'routeParam' => 'id',
            'title' => 'Laporan Management',
            'entity' => 'laporan',
            'showCreate' => false,
            'showFilter' => false,
            'searchPlaceholder' => 'Cari program...',
            'headerAction' => [
                'label' => 'Export',
                'url' => route('admin.reports.export'),
            ],
            'transformer' => function ($report) {
                $schedule = DB::table('schedules')
                    ->where('id_program', $report->id)
                    ->where('ket', 'Aktif')
                    ->first();

                $scheduleText = '-';
                if ($schedule) {
                    $startDate = $schedule->tanggal_mulai ? date('d/m/Y', strtotime($schedule->tanggal_mulai)) : '';
                    $endDate = $schedule->tanggal_selesai && $schedule->tanggal_selesai != $schedule->tanggal_mulai
                        ? date('d/m/Y', strtotime($schedule->tanggal_selesai))
                        : '';
                    $scheduleText = $endDate ? $startDate . ' - ' . $endDate : $startDate;
                }

                $totalCertified = DB::table('certificates')
                    ->where('program_id', $report->id)
                    ->count();

                return [
                    'id' => $report->id,
                    'title' => $report->title ?? 'N/A',
                    'schedule' => $scheduleText,
                    'total_certified_users' => $totalCertified
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.reports.index', compact('data'));
    }

    /**
     * Export reports to Excel/CSV
     */
    public function export()
    {
        // Get all reports data
        $reports = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.program as title',
                'data_programs.created_at'
            )
            ->orderBy('data_programs.created_at', 'desc')
            ->get();

        // Transform data
        $exportData = $reports->map(function ($report) {
            // Get schedule for this program
            $schedule = DB::table('schedules')
                ->where('id_program', $report->id)
                ->where('ket', 'Aktif')
                ->first();

            $scheduleText = '-';
            if ($schedule) {
                $startDate = $schedule->tanggal_mulai ? date('d/m/Y', strtotime($schedule->tanggal_mulai)) : '';
                $endDate = $schedule->tanggal_selesai && $schedule->tanggal_selesai != $schedule->tanggal_mulai
                    ? date('d/m/Y', strtotime($schedule->tanggal_selesai))
                    : '';
                $scheduleText = $endDate ? $startDate . ' - ' . $endDate : $startDate;
            }

            // Get total certified users for this program
            $totalCertified = DB::table('certificates')
                ->where('program_id', $report->id)
                ->count();

            return [
                'Judul Program' => $report->title ?? 'N/A',
                'Jadwal' => $scheduleText,
                'Total User Tersertifikasi' => $totalCertified,
                'Tanggal Dibuat' => $report->created_at ? date('d/m/Y H:i', strtotime($report->created_at)) : '-'
            ];
        });

        // Generate CSV
        $filename = 'laporan_program_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($exportData) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add headers
            if ($exportData->isNotEmpty()) {
                fputcsv($file, array_keys($exportData->first()), ';');
            }

            // Add data rows
            foreach ($exportData as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::table('reports')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus laporan'], 500);
        }
    }
}

