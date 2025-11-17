<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get program proofs from database with pagination (10 per page)
        $proofs = DB::table('program_proofs')
            ->leftJoin('data_siswas', 'program_proofs.student_id', '=', 'data_siswas.id')
            ->leftJoin('users', function($join) {
                $join->on('program_proofs.student_id', '=', 'users.id')
                     ->where('users.role', '=', 'user');
            })
            ->leftJoin('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->leftJoin('schedules', 'program_proofs.schedule_id', '=', 'schedules.id')
            ->select(
                'program_proofs.*',
                DB::raw('COALESCE(data_siswas.nama_lengkap, users.name) as student_name'),
                'data_programs.program as program_title',
                DB::raw("CONCAT(DATE_FORMAT(schedules.tanggal_mulai, '%d %M %Y'), IF(schedules.tanggal_selesai IS NOT NULL AND schedules.tanggal_selesai != schedules.tanggal_mulai, CONCAT(' - ', DATE_FORMAT(schedules.tanggal_selesai, '%d %M %Y')), '')) as schedule")
            )
            ->orderBy('program_proofs.created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $proofs->getCollection()->transform(function($proof) {
            return [
                'id' => $proof->id,
                'name' => $proof->student_name ?? 'N/A',
                'program_title' => $proof->program_title ?? 'N/A',
                'schedule' => $proof->schedule ?? '-',
                'documentation' => $proof->documentation,
                'status' => $proof->status
            ];
        });

        return view('admin.program-proofs.index', compact('proofs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proof = DB::table('program_proofs')
            ->leftJoin('data_siswas', 'program_proofs.student_id', '=', 'data_siswas.id')
            ->leftJoin('users', function($join) {
                $join->on('program_proofs.student_id', '=', 'users.id')
                     ->where('users.role', '=', 'user');
            })
            ->leftJoin('data_programs', 'program_proofs.program_id', '=', 'data_programs.id')
            ->leftJoin('schedules', 'program_proofs.schedule_id', '=', 'schedules.id')
            ->select(
                'program_proofs.*',
                DB::raw('COALESCE(data_siswas.nama_lengkap, users.name) as user_name'),
                'data_programs.program as program_title',
                'data_programs.type',
                'schedules.tanggal_mulai as start_date',
                'schedules.jam_mulai as start_time',
                'schedules.tanggal_selesai as end_date',
                'schedules.jam_selesai as end_time'
            )
            ->where('program_proofs.id', $id)
            ->first();

        if (!$proof) {
            return redirect()->route('admin.program-proofs.index')
                ->with('error', 'Bukti program tidak ditemukan.');
        }

        return view('admin.program-proofs.show', compact('proof'));
    }

    /**
     * Accept the program proof.
     */
    public function accept($id)
    {
        try {
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'accepted',
                    'accepted_by' => auth()->id(),
                    'accepted_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.program-proofs.index')->with('success', 'Bukti program berhasil diterima');
        } catch (\Exception $e) {
            return redirect()->route('admin.program-proofs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject the program proof.
     */
    public function reject($id)
    {
        try {
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.program-proofs.index')->with('success', 'Bukti program berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->route('admin.program-proofs.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $proof = DB::table('program_proofs')->where('id', $id)->first();
            if ($proof && $proof->documentation) {
                $filePath = public_path($proof->documentation);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            DB::table('program_proofs')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Bukti program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus bukti program'], 500);
        }
    }
}

