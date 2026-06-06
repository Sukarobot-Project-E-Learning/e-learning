<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RekapProgramController extends Controller
{
    /**
     * Display program recap dashboard.
     */
    public function index(Request $request)
    {
        $programs = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.slug',
                'data_programs.program as title',
                'data_programs.category',
                'data_programs.type',
                'data_programs.image',
                'data_programs.start_date',
                'data_programs.end_date',
                'data_programs.created_at'
            )
            ->orderBy('data_programs.created_at', 'desc')
            ->get();

        $programIds = $programs->pluck('id')->filter()->values();

        $categories = collect([
            ['key' => 'all', 'label' => 'Semua Kelas'],
        ]);

        $categoryLabels = $programs
            ->pluck('category')
            ->map(function ($category) {
                $label = trim((string) $category);
                return $label !== '' ? $label : null;
            })
            ->filter()
            ->unique()
            ->values();

        foreach ($categoryLabels as $label) {
            $categories->push([
                'key' => Str::slug($label),
                'label' => $label,
            ]);
        }

        if ($programIds->isEmpty()) {
            return view('admin.rekap-program.index', [
                'programs' => collect(),
                'categories' => $categories,
                'activeCategory' => $request->query('category', 'all'),
            ]);
        }

        $paidUserCounts = DB::table('transactions')
            ->select('program_id', DB::raw('COUNT(DISTINCT student_id) as total'))
            ->where('status', 'paid')
            ->whereIn('program_id', $programIds)
            ->groupBy('program_id')
            ->pluck('total', 'program_id');

        $earnedTotals = DB::table('transactions')
            ->select('program_id', DB::raw('COALESCE(SUM(amount), 0) as total'))
            ->where('status', 'paid')
            ->whereIn('program_id', $programIds)
            ->groupBy('program_id')
            ->pluck('total', 'program_id');

        $scheduleRows = DB::table('schedules')
            ->select('id_program', 'tanggal_mulai', 'tanggal_selesai', 'ket')
            ->whereIn('id_program', $programIds)
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ket, ''))) = 'aktif' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_mulai')
            ->get()
            ->groupBy('id_program')
            ->map(function ($rows) {
                return $rows->first();
            });

        $programs = $programs->map(function ($program) use ($paidUserCounts, $earnedTotals, $scheduleRows) {
            $scheduleRow = $scheduleRows->get($program->id);
            $scheduleText = $scheduleRow
                ? $this->formatScheduleRange($scheduleRow->tanggal_mulai ?? null, $scheduleRow->tanggal_selesai ?? null)
                : $this->formatScheduleRange($program->start_date ?? null, $program->end_date ?? null);

            $program->schedule_text = $scheduleText;
            $program->total_users = (int) ($paidUserCounts[$program->id] ?? 0);
            $program->total_earned = (int) ($earnedTotals[$program->id] ?? 0);
            $program->category_key = Str::slug((string) ($program->category ?? 'Lainnya'));

            return $program;
        });

        return view('admin.rekap-program.index', [
            'programs' => $programs,
            'categories' => $categories,
            'activeCategory' => $request->query('category', 'all'),
        ]);
    }

    /**
     * Display paid user details for a program.
     */
    public function show(string $slug)
    {
        $program = DB::table('data_programs')
            ->select(
                'data_programs.id',
                'data_programs.slug',
                'data_programs.program as title',
                'data_programs.category',
                'data_programs.type',
                'data_programs.image',
                'data_programs.start_date',
                'data_programs.end_date',
                'data_programs.created_at'
            )
            ->where('data_programs.slug', $slug)
            ->first();

        if (!$program) {
            abort(404);
        }

        $scheduleRow = DB::table('schedules')
            ->select('id_program', 'tanggal_mulai', 'tanggal_selesai', 'ket')
            ->where('id_program', $program->id)
            ->orderByRaw("CASE WHEN LOWER(TRIM(COALESCE(ket, ''))) = 'aktif' THEN 0 ELSE 1 END")
            ->orderByDesc('tanggal_mulai')
            ->first();

        $scheduleText = $scheduleRow
            ? $this->formatScheduleRange($scheduleRow->tanggal_mulai ?? null, $scheduleRow->tanggal_selesai ?? null)
            : $this->formatScheduleRange($program->start_date ?? null, $program->end_date ?? null);

        $paidUsers = DB::table('transactions')
            ->join('users', 'transactions.student_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.address',
                'transactions.amount',
                'transactions.payment_date',
                'transactions.created_at'
            )
            ->where('transactions.program_id', $program->id)
            ->where('transactions.status', 'paid')
            ->orderByRaw('COALESCE(transactions.payment_date, transactions.created_at) desc')
            ->get();

        $certificateUserIds = DB::table('certificates')
            ->where('program_id', $program->id)
            ->pluck('user_id')
            ->flip();

        $paidUsers = $paidUsers->map(function ($user) use ($certificateUserIds) {
            $user->is_completed = isset($certificateUserIds[$user->id]);
            $user->paid_at = $user->payment_date ?: $user->created_at;

            return $user;
        });

        $totalPaidUsers = $paidUsers->count();
        $totalRevenue = (int) $paidUsers->sum('amount');
        $completedUsers = $paidUsers->where('is_completed', true)->count();

        return view('admin.rekap-program.show', [
            'program' => $program,
            'scheduleText' => $scheduleText,
            'paidUsers' => $paidUsers,
            'totalPaidUsers' => $totalPaidUsers,
            'totalRevenue' => $totalRevenue,
            'completedUsers' => $completedUsers,
        ]);
    }

    /**
     * Format two dates into a single schedule label.
     */
    private function formatScheduleRange($startDate, $endDate): string
    {
        if (empty($startDate)) {
            return '-';
        }

        $start = Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y');

        if (!empty($endDate) && $endDate !== $startDate) {
            return $start . ' - ' . Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y');
        }

        return $start;
    }
}
