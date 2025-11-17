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
    public function index()
    {
        // Get reports from database with pagination (10 per page)
        // Reports are generated from programs with their schedules and certified users
        $reports = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.program as title',
                'data_programs.created_at'
            )
            ->orderBy('data_programs.created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $reports->getCollection()->transform(function($report) {
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

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Export reports to Excel
     */
    public function export()
    {
        // TODO: Implement Excel export
        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil diekspor');
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

