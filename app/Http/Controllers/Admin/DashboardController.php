<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get statistics from database
        $totalUsers = DB::table('users')->count();
        $totalAdmins = DB::table('users')->where('role', 'admin')->count();
        $totalTrainers = DB::table('data_trainers')->where('status_trainer', 'Aktif')->count();
        $totalStudents = DB::table('data_siswas')->where('status_siswa', 'Aktif')->count();
        $totalPrograms = DB::table('data_programs')->count();
        $totalLevels = DB::table('data_levels')->count();
        
        // Calculate total revenue from transactions table (only paid transactions)
        $totalRevenue = DB::table('transactions')
            ->where('status', 'paid')
            ->sum('amount') ?? 0;

        // Get recent programs
        $recentPrograms = DB::table('data_programs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active schedules count
        $activeSchedules = DB::table('schedules')
            ->where('ket', 'Aktif')
            ->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalTrainers',
            'totalStudents',
            'totalPrograms',
            'totalLevels',
            'totalRevenue',
            'activeSchedules',
            'recentPrograms'
        ));
    }
}

