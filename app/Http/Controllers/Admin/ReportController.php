<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
                'data_programs.start_date',
                'data_programs.end_date',
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
            'title' => 'Manajemen Laporan',
            'entity' => 'laporan',
            'showCreate' => false,
            'showFilter' => false,
            'searchPlaceholder' => 'Cari program...',
            'headerAction' => [
                'label' => 'Export',
                'url' => route('admin.reports.export'),
            ],
            'transformer' => function ($report) {
                $scheduleText = $this->resolveScheduleText($report->id, $report->start_date ?? null, $report->end_date ?? null);

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
     * Export reports to Excel (.xlsx)
     */
    public function export()
    {
        $reports = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.program as title',
                'data_programs.start_date',
                'data_programs.end_date',
                'data_programs.created_at'
            )
            ->orderBy('data_programs.created_at', 'desc')
            ->get();

        // ── Build spreadsheet ──
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('E-Learning Platform')
            ->setTitle('Laporan Program')
            ->setDescription('Exported program report data');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Program');

        $headers = ['Judul Program', 'Jadwal', 'Total Peserta Tersertifikasi', 'Tanggal Dibuat'];
        $colLetters = ['A', 'B', 'C', 'D'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($colLetters[$i] . '1', $header);
        }

        // ── Populate rows ──
        $row = 2;
        foreach ($reports as $report) {
            $clean = fn($v) => ($v === null || $v === '' || $v === 'N/A') ? '-' : $v;

            $scheduleText = $this->resolveScheduleText($report->id, $report->start_date ?? null, $report->end_date ?? null);

            $totalCertified = DB::table('certificates')
                ->where('program_id', $report->id)
                ->count();

            $sheet->setCellValue("A{$row}", $clean($report->title));
            $sheet->setCellValue("B{$row}", $scheduleText);
            $sheet->setCellValue("C{$row}", $totalCertified);
            $sheet->setCellValue("D{$row}", $report->created_at ? date('d/m/Y H:i', strtotime($report->created_at)) : '-');
            $row++;
        }

        $lastRow = max($row - 1, 1);
        $lastCol = end($colLetters);

        // ── Styling ──
        $this->applySpreadsheetStyles($sheet, $colLetters, $lastRow);

        // Center the numeric "Total" column
        $sheet->getStyle("C2:C{$lastRow}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font'      => ['bold' => true],
        ]);

        // ── Write & download ──
        $filename = 'laporan_program_' . date('Y-m-d_His') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'rpt_');
        (new Xlsx($spreadsheet))->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Resolve schedule text with fallback order:
     * 1) Active schedule in schedules table (case-insensitive ket)
     * 2) Latest available schedule row for the program
     * 3) Program start/end date from data_programs
     */
    private function resolveScheduleText(int $programId, $fallbackStartDate = null, $fallbackEndDate = null): string
    {
        $schedule = DB::table('schedules')
            ->where('id_program', $programId)
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ket, ''))) = 'aktif' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_mulai')
            ->first();

        if ($schedule) {
            return $this->formatScheduleRange($schedule->tanggal_mulai ?? null, $schedule->tanggal_selesai ?? null);
        }

        return $this->formatScheduleRange($fallbackStartDate, $fallbackEndDate);
    }

    /**
     * Format two dates into a single schedule label.
     */
    private function formatScheduleRange($startDate, $endDate): string
    {
        if (empty($startDate)) {
            return '-';
        }

        $start = date('d/m/Y', strtotime($startDate));

        if (!empty($endDate) && $endDate !== $startDate) {
            return $start . ' - ' . date('d/m/Y', strtotime($endDate));
        }

        return $start;
    }

    /**
     * Apply shared professional styling to any export sheet.
     */
    private function applySpreadsheetStyles($sheet, array $colLetters, int $lastRow): void
    {
        $lastCol = end($colLetters);
        $dataRange = "A1:{$lastCol}{$lastRow}";

        // ── Header row ──
        $headerRange = "A1:{$lastCol}1";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['argb' => 'FFFFFFFF'],
                'name'  => 'Segoe UI',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E293B'], // Slate-800
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF334155']],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Data body font ──
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:{$lastCol}{$lastRow}")->applyFromArray([
                'font' => ['size' => 10, 'name' => 'Segoe UI'],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // ── Alternating row bands (soft blue-gray) ──
        for ($r = 2; $r <= $lastRow; $r++) {
            $sheet->getRowDimension($r)->setRowHeight(22);
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF1F5F9']], // Slate-100
                ]);
            }
        }

        // ── Thin inner borders ──
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']], // Slate-300
            ],
        ]);

        // ── Outer border ──
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF64748B']], // Slate-500
            ],
        ]);

        // ── Auto-fit column widths ──
        foreach ($colLetters as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ── Freeze header row ──
        $sheet->freezePane('A2');

        // ── Print settings ──
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(0);
        $sheet->setAutoFilter($dataRange);
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

