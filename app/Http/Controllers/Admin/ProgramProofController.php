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
    /**
     * Accept the program proof and generate certificate.
     */
    public function accept($id)
    {
        try {
            // 1. Update status
            DB::table('program_proofs')
                ->where('id', $id)
                ->update([
                    'status' => 'accepted',
                    'accepted_by' => auth()->id(),
                    'accepted_at' => now(),
                    'updated_at' => now(),
                ]);

            // 2. Get Proof Data
            $proof = DB::table('program_proofs')->where('id', $id)->first();
            
            // 3. Check if certificate template exists for this program
            $template = DB::table('certificate_templates')->where('program_id', $proof->program_id)->first();

            if ($template) {
                // Get Student Name
                $student = DB::table('users')->where('id', $proof->student_id)->first();
                if (!$student) {
                    $student = DB::table('data_siswas')->where('id', $proof->student_id)->first();
                    $studentName = $student->nama_lengkap ?? 'Peserta';
                } else {
                    $studentName = $student->name;
                }

                // Generate Certificate Number
                // Format: 001/B-1/PT.STG/I/2025
                $count = DB::table('certificates')->count() + 1;
                $number = str_pad($count, 3, '0', STR_PAD_LEFT);
                $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $month = $romans[date('n') - 1];
                $year = date('Y');
                $certNumber = "No: {$number}/B-1/PT.STG/{$month}/{$year}";

                // Generate Image using GD
                $imagePath = storage_path('app/public/' . $template->background_image);
                
                if (file_exists($imagePath)) {
                    $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
                    if ($ext == 'png') {
                        $image = imagecreatefrompng($imagePath);
                    } else {
                        $image = imagecreatefromjpeg($imagePath);
                    }

                    // Colors
                    $black = imagecolorallocate($image, 0, 0, 0);
                    


                    // Decode positions
                    $namePos = json_decode($template->name_position);
                    $numPos = json_decode($template->number_position);
                    $descPos = json_decode($template->description_position);
                    $datePos = json_decode($template->date_position);

                    // Helper to center text
                    $addText = function($img, $size, $angle, $x, $y, $color, $fontFile, $text) {
                        // Resolve font path
                        $fontPath = public_path('assets/fonts/' . $fontFile);
                        if (!file_exists($fontPath) || empty($fontFile)) {
                            $fontPath = 'C:\Windows\Fonts\arial.ttf';
                        }

                        // Calculate center if x is center (e.g. if we want to center align)
                        // For now, assume X is the center point.
                        $bbox = imagettfbbox($size, $angle, $fontPath, $text);
                        $textWidth = $bbox[2] - $bbox[0];
                        $centeredX = $x - ($textWidth / 2);
                        imagettftext($img, $size, $angle, $centeredX, $y, $color, $fontPath, $text);
                    };

                    // Add Text
                    // 1. Number
                    $addText($image, $numPos->font_size ?? 12, 0, $numPos->x, $numPos->y, $black, $numPos->font ?? 'default', $certNumber);
                    
                    // 2. Name
                    $addText($image, $namePos->font_size ?? 38, 0, $namePos->x, $namePos->y, $black, $namePos->font ?? 'default', $studentName);

                    // 3. Description
                    // Word wrap description
                    $descText = $template->description;
                    // Simple word wrap logic could be added here if needed
                    $addText($image, $descPos->font_size ?? 16, 0, $descPos->x, $descPos->y, $black, $descPos->font ?? 'default', $descText);

                    // 4. Date
                    // Format: 01 Desember 2025
                    $months = [
                        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                    ];
                    $dateStr = date('d') . ' ' . $months[date('F')] . ' ' . date('Y');
                    $addText($image, $datePos->font_size ?? 16, 0, $datePos->x, $datePos->y, $black, $descPos->font ?? 'default', $dateStr);

                    // Save Certificate
                    $fileName = 'certificate_' . $proof->id . '_' . time() . '.jpg';
                    $savePath = storage_path('app/public/certificates/' . $fileName);
                    
                    // Ensure directory exists
                    if (!file_exists(dirname($savePath))) {
                        mkdir(dirname($savePath), 0755, true);
                    }

                    imagejpeg($image, $savePath, 90);
                    imagedestroy($image);

                    // Save to Database
                    DB::table('certificates')->insert([
                        'enrollment_id' => 0, // Assuming 0 or find enrollment if exists
                        'student_id' => $proof->student_id,
                        'program_id' => $proof->program_id,
                        'certificate_number' => $certNumber,
                        'certificate_file' => 'certificates/' . $fileName,
                        'issued_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return redirect()->route('admin.program-proofs.index')->with('success', 'Bukti program berhasil diterima dan sertifikat digenerate (jika template tersedia)');
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

