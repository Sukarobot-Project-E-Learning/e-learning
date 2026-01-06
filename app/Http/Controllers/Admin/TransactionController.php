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
    public function index()
    {
        // Get transactions from database with pagination (5 per page)
        $transactions = DB::table('transactions')
            ->leftJoin('users', 'transactions.student_id', '=', 'users.id')
            ->leftJoin('data_programs', 'transactions.program_id', '=', 'data_programs.id')
            ->select(
                'transactions.*',
                'users.name as student_name',
                'data_programs.program as program_name'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $transactions->getCollection()->transform(function($transaction) {
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
                'status' => $statusLabel,
                'transaction_code' => $transaction->transaction_code ?? '-'
            ];
        });

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Export transactions to Excel
     */
    public function export()
    {
        // TODO: Implement Excel export
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil diekspor');
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

