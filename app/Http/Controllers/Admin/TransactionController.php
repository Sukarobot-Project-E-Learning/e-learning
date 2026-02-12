<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'title' => 'Transaksi Management',
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

                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code ?? '-',
                    'name' => $transaction->student_name ?? 'N/A',
                    'program' => $transaction->program_name ?? 'N/A',
                    'proof' => $transaction->payment_proof,
                    'date' => $transaction->payment_date
                        ? date('d F Y', strtotime($transaction->payment_date))
                        : ($transaction->created_at ? date('d F Y', strtotime($transaction->created_at)) : '-'),
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
     * Export transactions to Excel/CSV
     */
    public function export()
    {
        // Get all transactions data
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

        // Format status mapping
        $statusMap = [
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            'cancelled' => 'Dibatalkan'
        ];

        // Transform data
        $exportData = $transactions->map(function ($transaction) use ($statusMap) {
            return [
                'Kode Transaksi' => $transaction->transaction_code ?? '-',
                'Nama Siswa' => $transaction->student_name ?? 'N/A',
                'Program' => $transaction->program_name ?? 'N/A',
                'Tanggal Pembayaran' => $transaction->payment_date
                    ? date('d/m/Y', strtotime($transaction->payment_date))
                    : ($transaction->created_at ? date('d/m/Y', strtotime($transaction->created_at)) : '-'),
                'Nominal' => $transaction->amount ?? 0,
                'Status' => $statusMap[$transaction->status] ?? $transaction->status,
                'Tanggal Dibuat' => $transaction->created_at ? date('d/m/Y H:i', strtotime($transaction->created_at)) : '-'
            ];
        });

        // Generate CSV
        $filename = 'transaksi_' . date('Y-m-d_His') . '.csv';

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

