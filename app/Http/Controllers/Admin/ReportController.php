<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = (int) $request->input('per_page', 10);
        $sortKey = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');

        // Validate sort direction
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'desc';

        // Map sortable columns
        $sortableColumns = [
            'title' => 'data_programs.program',
            'created_at' => 'data_programs.created_at',
        ];
        $sortColumn = $sortableColumns[$sortKey] ?? 'data_programs.created_at';

        // Build query
        $query = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.program as title',
                'data_programs.created_at'
            );

        // Apply search filter
        if ($search) {
            $query->where('data_programs.program', 'like', "%{$search}%");
        }

        // Apply sorting and pagination
        $reports = $query->orderBy($sortColumn, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $reports->getCollection()->transform(function ($report) {
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
                'id' => $report->id,
                'title' => $report->title ?? 'N/A',
                'schedule' => $scheduleText,
                'total_certified_users' => $totalCertified
            ];
        });

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'data' => $reports->items(),
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ]);
        }

        return view('admin.reports.index', compact('reports'));
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

