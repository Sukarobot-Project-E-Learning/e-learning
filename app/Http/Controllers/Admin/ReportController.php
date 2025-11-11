<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $reports = [
            [
                'id' => 1,
                'title' => 'Workshop Bradning',
                'schedule' => '20/10/2025',
                'total_certified_users' => '100'
            ],
            [
                'id' => 2,
                'title' => 'Workshop Bradning',
                'schedule' => '20/10/2025',
                'total_certified_users' => '100'
            ],
            [
                'id' => 3,
                'title' => 'Workshop Bradning',
                'schedule' => '20/10/2025',
                'total_certified_users' => '100'
            ],
            [
                'id' => 4,
                'title' => 'Workshop Bradning',
                'schedule' => '20/10/2025',
                'total_certified_users' => '100'
            ],
            [
                'id' => 5,
                'title' => 'Workshop Bradning',
                'schedule' => '20/10/2025',
                'total_certified_users' => '100'
            ],
        ];

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Export reports to Excel
     */
    public function export()
    {
        // TODO: Implement Excel export
        return redirect()->route('elearning.admin.reports.index')
            ->with('success', 'Laporan berhasil diekspor');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database

        return redirect()->route('elearning.admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
}

