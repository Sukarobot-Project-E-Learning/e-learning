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
        $totalStudents = DB::table('users')->where('role', 'user')->where('is_active', 1)->count();
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
     * Get chart data - automatically detect years with actual data
     */
    private function getChartData()
    {
        // Get the earliest and latest year from actual data
        $earliestUserDate = DB::table('users')
            ->whereNotNull('created_at')
            ->min('created_at');
        
        $earliestProgramDate = DB::table('data_programs')
            ->whereNotNull('created_at')
            ->min('created_at');
        
        $latestUserDate = DB::table('users')
            ->whereNotNull('created_at')
            ->max('created_at');
        
        $latestProgramDate = DB::table('data_programs')
            ->whereNotNull('created_at')
            ->max('created_at');
        
        // Determine year range based on actual data
        $minYear = min(
            $earliestUserDate ? Carbon::parse($earliestUserDate)->year : now()->year,
            $earliestProgramDate ? Carbon::parse($earliestProgramDate)->year : now()->year
        );
        
        $maxYear = max(
            $latestUserDate ? Carbon::parse($latestUserDate)->year : now()->year,
            $latestProgramDate ? Carbon::parse($latestProgramDate)->year : now()->year
        );
        
        // Only include current year if it has data or if it's within the range
        $currentYear = now()->year;
        if ($currentYear < $minYear || $currentYear > $maxYear) {
            // Check if current year has any data
            $hasCurrentYearData = DB::table('users')
                ->whereYear('created_at', $currentYear)
                ->exists() || DB::table('data_programs')
                ->whereYear('created_at', $currentYear)
                ->exists() || DB::table('transactions')
                ->whereYear('created_at', $currentYear)
                ->exists();
                
            if ($hasCurrentYearData) {
                $maxYear = max($maxYear, $currentYear);
                $minYear = min($minYear, $currentYear);
            }
        }
        
        // Generate years array from min to max
        $years = range($maxYear, $minYear); // Descending order (newest first)
        
        $chartDataByYear = [];

        foreach ($years as $year) {
            $months = [];
            $revenueData = [];
            $usersData = [];
            $instructorsData = [];
            $programsData = [];

            // Get 12 months for this year
            $yearStart = Carbon::create($year, 1, 1)->startOfYear();
            
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create($year, $month, 1);
                $monthEnd = $date->copy()->endOfMonth();
                
                $months[] = $date->format('M');
                
                // Revenue data - cumulative within this year only
                $revenue = DB::table('transactions')
                    ->where('status', 'paid')
                    ->where('created_at', '>=', $yearStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->sum('amount') ?? 0;
                $revenueData[] = (int) $revenue;
                
                // Users data - cumulative within this year only
                $users = DB::table('users')
                    ->where('role', 'user')
                    ->where('created_at', '>=', $yearStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                $usersData[] = $users;
                
                // Instructors data - cumulative within this year only
                $instructors = DB::table('users')
                    ->where('role', 'instructor')
                    ->where('created_at', '>=', $yearStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                $instructorsData[] = $instructors;
                
                // Programs data - cumulative within this year only
                $programs = DB::table('data_programs')
                    ->where('created_at', '>=', $yearStart)
                    ->where('created_at', '<=', $monthEnd)
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

