<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('transactions')
            ->leftJoin('users', 'transactions.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'transactions.program_id', '=', 'data_programs.id')
            ->select(
                'transactions.id',
                'transactions.transaction_code',
                'transactions.amount',
                'transactions.status',
                'transactions.payment_date',
                'transactions.payment_proof',
                'transactions.created_at',
                'users.name as student_name',
                'data_programs.program as program_name'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'transaction_code', 'label' => 'Kode Transaksi', 'type' => 'primary'],
                ['key' => 'name', 'label' => 'Nama Siswa', 'sortable' => true],
                ['key' => 'program', 'label' => 'Program', 'sortable' => true],
                ['key' => 'nominal', 'label' => 'Nominal', 'sortable' => true, 'type' => 'currency'],
                ['key' => 'date', 'label' => 'Tanggal', 'sortable' => true, 'type' => 'date'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['users.name', 'data_programs.program', 'transactions.transaction_code'],
            'sortable' => ['student_name', 'program_name', 'payment_date', 'amount', 'status', 'created_at'],
            'sortColumns' => [
                'name' => 'users.name',
                'program' => 'data_programs.program',
                'nominal' => 'transactions.amount',
                'date' => 'transactions.payment_date',
                'status' => 'transactions.status',
            ],
            'actions' => ['view'],
            'route' => 'admin.transactions',
            'routeParam' => 'id',
            'title' => 'Manajemen Transaksi',
            'entity' => 'transaksi',
            'showCreate' => false,
            'searchPlaceholder' => 'Cari nama, program, kode transaksi...',
            'headerAction' => [
                'label' => 'Export',
                'url' => route('admin.transactions.export'),
            ],
            'filter' => [
                'key' => 'status',
                'column' => 'transactions.status',
                'options' => [
                    '' => 'Semua Status',
                    'pending' => 'Menunggu',
                    'paid' => 'Lunas',
                    'failed' => 'Gagal',
                    'refunded' => 'Dikembalikan',
                    'cancelled' => 'Dibatalkan',
                ]
            ],
            'badgeClasses' => [
                'Lunas' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                'Gagal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                'Dikembalikan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                'Dibatalkan' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            ],
            'transformer' => function ($transaction) {
                $statusMap = [
                    'pending' => 'Menunggu',
                    'paid' => 'Lunas',
                    'failed' => 'Gagal',
                    'refunded' => 'Dikembalikan',
                    'cancelled' => 'Dibatalkan'
                ];
                $statusLabel = $statusMap[$transaction->status] ?? $transaction->status;

                $dateSource = $transaction->payment_date ?: $transaction->created_at;
                $formattedDate = '-';
                if ($dateSource) {
                    $formattedDate = Carbon::parse($dateSource)->locale('id')->translatedFormat('d F Y');
                    $formattedDate = mb_strtolower($formattedDate);
                }

                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code ?? '-',
                    'name' => $transaction->student_name ?? 'N/A',
                    'program' => $transaction->program_name ?? 'N/A',
                    'proof' => $transaction->payment_proof,
                    'date' => $formattedDate,
                    'nominal' => $transaction->amount ?? 0,
                    'amount_raw' => $transaction->amount,
                    'status' => $statusLabel,
                    'status_raw' => $transaction->status,
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.transactions.index', compact('data'));
    }

    /**
     * Export transactions to Excel (.xlsx)
     */
    public function export()
    {
        $transactions = DB::table('transactions')
            ->leftJoin('users', 'transactions.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'transactions.program_id', '=', 'data_programs.id')
            ->select(
                'transactions.*',
                'users.name as student_name',
                'data_programs.program as program_name'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->get();

        $statusMap = [
            'pending'   => 'Menunggu',
            'paid'      => 'Lunas',
            'failed'    => 'Gagal',
            'refunded'  => 'Dikembalikan',
            'cancelled' => 'Dibatalkan',
        ];

        // ── Build spreadsheet ──
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('E-Learning Platform')
            ->setTitle('Data Transaksi')
            ->setDescription('Exported transaction data');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transaksi');

        $headers = ['Kode Transaksi', 'Nama Siswa', 'Program', 'Tanggal Pembayaran', 'Nominal (Rp)', 'Status', 'Tanggal Dibuat'];
        $colLetters = range('A', 'G');

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($colLetters[$i] . '1', $header);
        }

        // ── Populate rows ──
        $row = 2;
        foreach ($transactions as $tx) {
            $clean = fn($v) => ($v === null || $v === '' || $v === 'N/A') ? '-' : $v;

            $sheet->setCellValue("A{$row}", $clean($tx->transaction_code));
            $sheet->setCellValue("B{$row}", $clean($tx->student_name));
            $sheet->setCellValue("C{$row}", $clean($tx->program_name));
            $sheet->setCellValue("D{$row}", $tx->payment_date
                ? date('d/m/Y', strtotime($tx->payment_date))
                : ($tx->created_at ? date('d/m/Y', strtotime($tx->created_at)) : '-'));
            $sheet->setCellValue("E{$row}", $tx->amount ?? 0);
            $sheet->setCellValue("F{$row}", $statusMap[$tx->status] ?? $tx->status);
            $sheet->setCellValue("G{$row}", $tx->created_at ? date('d/m/Y H:i', strtotime($tx->created_at)) : '-');
            $row++;
        }

        $lastRow = max($row - 1, 1);
        $lastCol = end($colLetters);

        // ── Styling ──
        $this->applySpreadsheetStyles($sheet, $colLetters, $lastRow);

        // Currency format for Nominal column
        $sheet->getStyle("E2:E{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Status badge colouring
        $statusColors = [
            'Lunas'        => ['bg' => 'FFDCFCE7', 'fg' => 'FF166534'],
            'Menunggu'     => ['bg' => 'FFFEF9C3', 'fg' => 'FF854D0E'],
            'Gagal'        => ['bg' => 'FFFEE2E2', 'fg' => 'FF991B1B'],
            'Dikembalikan' => ['bg' => 'FFDBEAFE', 'fg' => 'FF1E40AF'],
            'Dibatalkan'   => ['bg' => 'FFF3F4F6', 'fg' => 'FF374151'],
        ];
        for ($r = 2; $r <= $lastRow; $r++) {
            $val = $sheet->getCell("F{$r}")->getValue();
            if (isset($statusColors[$val])) {
                $sheet->getStyle("F{$r}")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['argb' => $statusColors[$val]['fg']]],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $statusColors[$val]['bg']]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            }
        }

        // ── Write & download ──
        $filename = 'transaksi_' . date('Y-m-d_His') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'tx_');
        (new Xlsx($spreadsheet))->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
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
            $transaction = DB::table('transactions')->where('id', $id)->first();
            if ($transaction && $transaction->payment_proof) {
                $filePath = public_path($transaction->payment_proof);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            DB::table('transactions')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus transaksi'], 500);
        }
    }
}

