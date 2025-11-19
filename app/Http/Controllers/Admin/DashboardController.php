<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $totalUsers = DB::table('users')->where('role', 'user')->count();
        $totalInstructors = DB::table('users')->where('role', 'instructor')->count();
        $totalAdmins = DB::table('users')->where('role', 'admin')->count();
        $totalTrainers = DB::table('data_trainers')->where('status_trainer', 'Aktif')->count();
        $totalStudents = DB::table('data_siswas')->where('status_siswa', 'Aktif')->count();
        $totalPrograms = DB::table('data_programs')->count();
        
        // Program tersedia (status = published)
        $programsAvailable = DB::table('data_programs')
            ->where('status', 'published')
            ->count();
        
        // Program tidak tersedia (status != published)
        $programsUnavailable = DB::table('data_programs')
            ->whereIn('status', ['draft', 'archived'])
            ->count();
        
        // Calculate total revenue from transactions table (only paid transactions)
        $totalRevenue = DB::table('transactions')
            ->where('status', 'paid')
            ->sum('amount') ?? 0;

        // Get recent programs
        $recentPrograms = DB::table('data_programs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get chart data for multiple years
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalInstructors',
            'totalAdmins',
            'totalTrainers',
            'totalStudents',
            'totalPrograms',
            'programsAvailable',
            'programsUnavailable',
            'totalRevenue',
            'recentPrograms',
            'chartData'
        ));
    }

    /**
     * Get chart data for multiple years (2021-2024)
     */
    private function getChartData()
    {
        $years = [2024, 2023, 2022, 2021];
        $chartDataByYear = [];

        foreach ($years as $year) {
            $months = [];
            $revenueData = [];
            $usersData = [];
            $instructorsData = [];
            $programsData = [];

            // Get 12 months for this year
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                $months[] = $date->format('M');
                
                // Revenue data (from transactions)
                $revenue = DB::table('transactions')
                    ->where('status', 'paid')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('amount') ?? 0;
                $revenueData[] = (int) $revenue;
                
                // Users data (total users created in that month)
                $users = DB::table('users')
                    ->where('role', 'user')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();
                $usersData[] = $users;
                
                // Instructors data (total instructors created in that month)
                $instructors = DB::table('users')
                    ->where('role', 'instructor')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();
                $instructorsData[] = $instructors;
                
                // Programs data (total programs created in that month)
                $programs = DB::table('data_programs')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();
                $programsData[] = $programs;
            }

            $chartDataByYear[$year] = [
                'months' => $months,
                'revenue' => $revenueData,
                'users' => $usersData,
                'instructors' => $instructorsData,
                'programs' => $programsData
            ];
        }

        return $chartDataByYear;
    }
}

