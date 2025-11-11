<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $transactions = [
            [
                'id' => 1,
                'name' => 'Nama User',
                'proof' => null,
                'date' => '20 September 2025',
                'nominal' => 'Rp. 100.000',
                'status' => 'Lunas'
            ],
            [
                'id' => 2,
                'name' => 'Nama User',
                'proof' => null,
                'date' => '20 September 2025',
                'nominal' => 'Rp. 100.000',
                'status' => 'Lunas'
            ],
            [
                'id' => 3,
                'name' => 'Nama User',
                'proof' => null,
                'date' => '20 September 2025',
                'nominal' => 'Rp. 100.000',
                'status' => 'Lunas'
            ],
            [
                'id' => 4,
                'name' => 'Nama User',
                'proof' => null,
                'date' => '20 September 2025',
                'nominal' => 'Rp. 100.000',
                'status' => 'Lunas'
            ],
            [
                'id' => 5,
                'name' => 'Nama User',
                'proof' => null,
                'date' => '20 September 2025',
                'nominal' => 'Rp. 100.000',
                'status' => 'Lunas'
            ],
        ];

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Export transactions to Excel
     */
    public function export()
    {
        // TODO: Implement Excel export
        return redirect()->route('elearning.admin.transactions.index')
            ->with('success', 'Transaksi berhasil diekspor');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // TODO: Delete file if exists

        return redirect()->route('elearning.admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}

