<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['student_name', 'program_name', 'payment_date', 'amount', 'status', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Map sort keys to actual column names
        $sortColumn = match ($sortKey) {
            'student_name' => 'users.name',
            'program_name' => 'data_programs.program',
            default => 'transactions.' . $sortKey
        };

        $query = DB::table('transactions')
            ->leftJoin('users', 'transactions.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'transactions.program_id', '=', 'data_programs.id')
            ->select(
                'transactions.*',
                'users.name as student_name',
                'data_programs.program as program_name'
            )
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('users.name', 'like', '%' . $s . '%')
                        ->orWhere('data_programs.program', 'like', '%' . $s . '%')
                        ->orWhere('transactions.transaction_code', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('transactions.status', $request->input('status'));
            });

        $transactions = $query->orderBy($sortColumn, $dir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $transactions->getCollection()->transform(function ($transaction) {
            // Format status
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
                'name' => $transaction->student_name ?? 'N/A',
                'program' => $transaction->program_name ?? 'N/A',
                'proof' => $transaction->payment_proof,
                'date' => $transaction->payment_date
                    ? date('d F Y', strtotime($transaction->payment_date))
                    : ($transaction->created_at ? date('d F Y', strtotime($transaction->created_at)) : '-'),
                'nominal' => 'Rp. ' . number_format($transaction->amount, 0, ',', '.'),
                'amount_raw' => $transaction->amount,
                'status' => $statusLabel,
                'status_raw' => $transaction->status,
                'transaction_code' => $transaction->transaction_code ?? '-'
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($transactions);
        }

        return view('admin.transactions.index', compact('transactions'));
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

