<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificate templates.
     */
    public function index()
    {
        $templates = DB::table('certificate_templates')
            ->leftJoin('data_programs', 'certificate_templates.program_id', '=', 'data_programs.id')
            ->select(
                'certificate_templates.*',
                'data_programs.program as program_name'
            )
            ->orderBy('certificate_templates.created_at', 'desc')
            ->paginate(10);

        $templates->getCollection()->transform(function($template) {
            $certificatesCount = DB::table('certificates')
                ->where('template_id', $template->id)
                ->count();
            
            return [
                'id' => $template->id,
                'program_name' => $template->program_name ?? 'N/A',
                'program_id' => $template->program_id,
                'number_prefix' => $template->number_prefix,
                'description' => $template->description,
                'template_path' => $template->template_path ? asset($template->template_path) : null,
                'is_active' => $template->is_active,
                'certificates_count' => $certificatesCount,
                'created_at' => $template->created_at ? date('d F Y', strtotime($template->created_at)) : '-'
            ];
        });

        return view('admin.certificates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new certificate template.
     */
    public function create()
    {
        $existingTemplatePrograms = DB::table('certificate_templates')->pluck('program_id')->toArray();
        
        $programs = DB::table('data_programs')
            ->select('id', 'program')
            ->where('status', 'published')
            ->whereNotIn('id', $existingTemplatePrograms)
            ->orderBy('program', 'asc')
            ->get();

        $currentMonth = $this->romanMonth(Carbon::now()->month);
        $currentYear = Carbon::now()->year;

        // Default positions and font sizes (must be 100% zoom scale)
        $defaults = [
            'name_x' => 54, 'name_y' => 41, 'name_font_size' => 85,
            'desc_x' => 54, 'desc_y' => 54, 'desc_font_size' => 31,
            'number_x' => 54, 'number_y' => 30.5, 'number_font_size' => 30,
            'date_x' => 54, 'date_y' => 68, 'date_font_size' => 30,
        ];

        return view('admin.certificates.create', compact(
            'programs',
            'currentMonth',
            'currentYear',
            'defaults'
        ));
    }

    /**
     * Store a newly created certificate template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:data_programs,id|unique:certificate_templates,program_id',
            'number_prefix' => 'required|string|max:100',
            'description' => 'nullable|string',
            'blanko' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'name_x' => 'required|numeric|min:0|max:100',
            'name_y' => 'required|numeric|min:0|max:100',
            'desc_x' => 'required|numeric|min:0|max:100',
            'desc_y' => 'required|numeric|min:0|max:100',
            'number_x' => 'required|numeric|min:0|max:100',
            'number_y' => 'required|numeric|min:0|max:100',
            'date_x' => 'required|numeric|min:0|max:100',
            'date_y' => 'required|numeric|min:0|max:100',
            'name_font_size' => 'required|integer|min:8|max:100',
            'desc_font_size' => 'required|integer|min:8|max:100',
            'number_font_size' => 'required|integer|min:8|max:100',
            'date_font_size' => 'required|integer|min:8|max:100',
        ], [
            'program_id.unique' => 'Template untuk program ini sudah ada.',
        ]);

        // Upload blanko template
        $blankoFile = $request->file('blanko');
        $blankoName = time() . '_' . Str::random(6) . '.' . $blankoFile->getClientOriginalExtension();
        $blankoDir = public_path('uploads/certificates/templates');
        if (!is_dir($blankoDir)) {
            mkdir($blankoDir, 0755, true);
        }
        $blankoFile->move($blankoDir, $blankoName);
        $templateRelativePath = 'uploads/certificates/templates/' . $blankoName;

        DB::table('certificate_templates')->insert([
            'program_id' => $request->input('program_id'),
            'number_prefix' => $request->input('number_prefix'),
            'description' => $request->input('description'),
            'template_path' => $templateRelativePath,
            'is_active' => 1,
            'name_x' => $request->input('name_x'),
            'name_y' => $request->input('name_y'),
            'desc_x' => $request->input('desc_x'),
            'desc_y' => $request->input('desc_y'),
            'number_x' => $request->input('number_x'),
            'number_y' => $request->input('number_y'),
            'date_x' => $request->input('date_x'),
            'date_y' => $request->input('date_y'),
            'name_font_size' => $request->input('name_font_size'),
            'desc_font_size' => $request->input('desc_font_size'),
            'number_font_size' => $request->input('number_font_size'),
            'date_font_size' => $request->input('date_font_size'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Template sertifikat berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified certificate template.
     */
    public function edit($id)
    {
        $template = DB::table('certificate_templates')
            ->leftJoin('data_programs', 'certificate_templates.program_id', '=', 'data_programs.id')
            ->select('certificate_templates.*', 'data_programs.program as program_name')
            ->where('certificate_templates.id', $id)
            ->first();

        if (!$template) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Template tidak ditemukan');
        }

        $programs = DB::table('data_programs')
            ->select('id', 'program')
            ->where('status', 'published')
            ->orderBy('program', 'asc')
            ->get();

        return view('admin.certificates.edit', compact('template', 'programs'));
    }

    /**
     * Update the specified certificate template.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'number_prefix' => 'required|string|max:100',
            'description' => 'nullable|string',
            'blanko' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'name_x' => 'required|numeric|min:0|max:100',
            'name_y' => 'required|numeric|min:0|max:100',
            'desc_x' => 'required|numeric|min:0|max:100',
            'desc_y' => 'required|numeric|min:0|max:100',
            'number_x' => 'required|numeric|min:0|max:100',
            'number_y' => 'required|numeric|min:0|max:100',
            'date_x' => 'required|numeric|min:0|max:100',
            'date_y' => 'required|numeric|min:0|max:100',
            'name_font_size' => 'required|integer|min:8|max:100',
            'desc_font_size' => 'required|integer|min:8|max:100',
            'number_font_size' => 'required|integer|min:8|max:100',
            'date_font_size' => 'required|integer|min:8|max:100',
        ]);

        $template = DB::table('certificate_templates')->where('id', $id)->first();
        if (!$template) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Template tidak ditemukan');
        }

        $updateData = [
            'number_prefix' => $request->input('number_prefix'),
            'description' => $request->input('description'),
            'name_x' => $request->input('name_x'),
            'name_y' => $request->input('name_y'),
            'desc_x' => $request->input('desc_x'),
            'desc_y' => $request->input('desc_y'),
            'number_x' => $request->input('number_x'),
            'number_y' => $request->input('number_y'),
            'date_x' => $request->input('date_x'),
            'date_y' => $request->input('date_y'),
            'name_font_size' => $request->input('name_font_size'),
            'desc_font_size' => $request->input('desc_font_size'),
            'number_font_size' => $request->input('number_font_size'),
            'date_font_size' => $request->input('date_font_size'),
            'updated_at' => Carbon::now(),
        ];

        if ($request->hasFile('blanko')) {
            $blankoFile = $request->file('blanko');
            $blankoName = time() . '_' . Str::random(6) . '.' . $blankoFile->getClientOriginalExtension();
            $blankoDir = public_path('uploads/certificates/templates');
            if (!is_dir($blankoDir)) {
                mkdir($blankoDir, 0755, true);
            }
            $blankoFile->move($blankoDir, $blankoName);
            
            if ($template->template_path) {
                $oldPath = public_path($template->template_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $updateData['template_path'] = 'uploads/certificates/templates/' . $blankoName;
        }

        DB::table('certificate_templates')->where('id', $id)->update($updateData);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Template sertifikat berhasil diperbarui');
    }

    /**
     * Remove the specified certificate template.
     */
    public function destroy($id)
    {
        try {
            $template = DB::table('certificate_templates')->where('id', $id)->first();
            
            if ($template) {
                $certificatesCount = DB::table('certificates')
                    ->where('template_id', $id)
                    ->count();
                
                if ($certificatesCount > 0) {
                    return response()->json([
                        'success' => false, 
                        'message' => "Tidak dapat menghapus template karena sudah ada {$certificatesCount} sertifikat yang menggunakannya."
                    ], 400);
                }
                
                if ($template->template_path) {
                    $filePath = public_path($template->template_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            DB::table('certificate_templates')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Template sertifikat berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus template'], 500);
        }
    }

    /**
     * Show list of generated certificates
     */
    public function generatedCertificates()
    {
        $certificates = DB::table('certificates')
            ->leftJoin('certificate_templates', 'certificates.template_id', '=', 'certificate_templates.id')
            ->leftJoin('data_programs', 'certificates.program_id', '=', 'data_programs.id')
            ->leftJoin('users', 'certificates.user_id', '=', 'users.id')
            ->select(
                'certificates.*',
                'data_programs.program as program_name',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('certificates.created_at', 'desc')
            ->paginate(10);

        return view('admin.certificates.generated', compact('certificates'));
    }

    // ============================================================
    // STATIC HELPER METHODS FOR CERTIFICATE GENERATION
    // ============================================================

    /**
     * Generate certificate for a user when their proof is approved.
     */
    public static function generateCertificateForUser(int $templateId, int $programId, int $userId, ?int $proofId = null): array
    {
        $template = DB::table('certificate_templates')->where('id', $templateId)->first();
        if (!$template) {
            return ['success' => false, 'message' => 'Template sertifikat tidak ditemukan'];
        }

        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            return ['success' => false, 'message' => 'User tidak ditemukan'];
        }

        $existing = DB::table('certificates')
            ->where('user_id', $userId)
            ->where('program_id', $programId)
            ->first();

        if ($existing) {
            return ['success' => false, 'message' => 'User sudah memiliki sertifikat untuk program ini'];
        }

        $issuedAt = Carbon::now();
        $certificateNumber = self::generateCertificateNumber($template->number_prefix);

        // Get position and font size settings from template
        $settings = [
            'name_x' => $template->name_x ?? 54,
            'name_y' => $template->name_y ?? 41,
            'desc_x' => $template->desc_x ?? 51.9,
            'desc_y' => $template->desc_y ?? 56,
            'number_x' => $template->number_x ?? 53.9,
            'number_y' => $template->number_y ?? 30.7,
            'date_x' => $template->date_x ?? 54,
            'date_y' => $template->date_y ?? 68,
            'name_font_size' => $template->name_font_size ?? 70,
            'desc_font_size' => $template->desc_font_size ?? 37,
            'number_font_size' => $template->number_font_size ?? 30,
            'date_font_size' => $template->date_font_size ?? 30,
        ];

        // Generate certificate image with custom positions and font sizes
        $generatedPath = self::renderCertificateImage(
            public_path($template->template_path),
            $certificateNumber,
            $user->name,
            $template->description,
            $issuedAt,
            $settings
        );

        $certificateId = DB::table('certificates')->insertGetId([
            'template_id' => $templateId,
            'program_id' => $programId,
            'user_id' => $userId,
            'proof_id' => $proofId,
            'recipient_name' => $user->name,
            'certificate_number' => $certificateNumber,
            'certificate_file' => $generatedPath,
            'issued_at' => $issuedAt->toDateString(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return [
            'success' => true,
            'message' => 'Sertifikat berhasil di-generate',
            'certificate_id' => $certificateId,
            'certificate_number' => $certificateNumber,
            'certificate_file' => $generatedPath,
        ];
    }

    public static function hasTemplateForProgram(int $programId): bool
    {
        return DB::table('certificate_templates')
            ->where('program_id', $programId)
            ->where('is_active', 1)
            ->exists();
    }

    public static function getTemplateForProgram(int $programId)
    {
        return DB::table('certificate_templates')
            ->where('program_id', $programId)
            ->where('is_active', 1)
            ->first();
    }

    private static function generateCertificateNumber(string $prefix): string
    {
        $lastNumber = DB::table('certificates')
            ->orderByDesc('id')
            ->value('certificate_number');

        $next = 1;
        if ($lastNumber && preg_match('/^(\d{3})/', $lastNumber, $matches)) {
            $next = ((int)$matches[1]) + 1;
        }

        $sequence = str_pad((string)$next, 3, '0', STR_PAD_LEFT);
        $romanMonth = self::getRomanMonth(Carbon::now()->month);
        $year = Carbon::now()->year;

        return "{$sequence}/{$prefix}/{$romanMonth}/{$year}";
    }

    private static function getRomanMonth(int $month): string
    {
        $romans = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romans[$month] ?? '';
    }

    private function romanMonth(int $month): string
    {
        return self::getRomanMonth($month);
    }

    private static function formatIndonesianDate(Carbon $date): string
    {
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return $date->format('d') . ' ' . ($months[$date->month] ?? $date->format('F')) . ' ' . $date->format('Y');
    }

    /**
     * Render certificate by overlaying text on blanko template.
     * Uses custom positions and font sizes from template settings.
     */
    private static function renderCertificateImage(
        string $templateFullPath,
        string $certificateNumber,
        string $recipientName,
        ?string $description,
        Carbon $issuedAt,
        array $settings
    ): ?string {
        if (!file_exists($templateFullPath) || !extension_loaded('gd')) {
            return null;
        }

        $imageInfo = getimagesize($templateFullPath);
        if (!$imageInfo) {
            return null;
        }

        $imageType = $imageInfo[2];
        switch ($imageType) {
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($templateFullPath);
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($templateFullPath);
                break;
            default:
                return null;
        }

        if (!$im) {
            return null;
        }

        imagesavealpha($im, true);
        
        $textColorDark = imagecolorallocate($im, 25, 25, 25);
        $width = imagesx($im);
        $height = imagesy($im);

        // Font paths
        $latoFont = public_path('fonts/Lato-Regular.ttf');
        $lobsterFont = public_path('fonts/Lobster-Regular.ttf');

        $useLato = file_exists($latoFont);
        $useLobster = file_exists($lobsterFont);

        // Font sizes from template settings
        $sizeNumber = $settings['number_font_size'] ?? 30;
        $sizeName = $settings['name_font_size'] ?? 70;
        $sizeDescription = $settings['desc_font_size'] ?? 37;
        $sizeDate = $settings['date_font_size'] ?? 30;

        /**
         * Draw text at specified X,Y position (percentage based)
         */
        $drawAtPosition = function (string $text, string $fontPath, int $fontSize, float $xPercent, float $yPercent, $color) use ($im, $width, $height) {
            if (!file_exists($fontPath)) {
                $textWidth = strlen($text) * imagefontwidth(5);
                $x = ($width * $xPercent / 100) - ($textWidth / 2);
                $y = $height * $yPercent / 100;
                imagestring($im, 5, (int)$x, (int)$y, $text, $color);
                return;
            }
            $box = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = $box[2] - $box[0];
            $x = ($width * $xPercent / 100) - ($textWidth / 2);
            $y = $height * $yPercent / 100;
            imagettftext($im, $fontSize, 0, (int)$x, (int)$y, $color, $fontPath, $text);
        };

        // Draw recipient name (Lobster font)
        $nameFont = $useLobster ? $lobsterFont : $latoFont;
        $drawAtPosition($recipientName, $nameFont, $sizeName, $settings['name_x'], $settings['name_y'], $textColorDark);

        // Draw certificate number (Lato font)
        $drawAtPosition("No: " . $certificateNumber, $latoFont, $sizeNumber, $settings['number_x'], $settings['number_y'], $textColorDark);

        // Draw description (Lato font) - with word wrap
        if ($description) {
            $wrapped = wordwrap($description, 60, "\n");
            $lines = explode("\n", $wrapped);
            $lineHeightPercent = 3.5; // spacing between lines in percentage
            $startY = $settings['desc_y'];
            foreach ($lines as $index => $line) {
                $yPos = $startY + ($lineHeightPercent * $index);
                $drawAtPosition(trim($line), $latoFont, $sizeDescription, $settings['desc_x'], $yPos, $textColorDark);
            }
        }

        // Draw date (Lato font)
        $dateText = self::formatIndonesianDate($issuedAt);
        $drawAtPosition($dateText, $latoFont, $sizeDate, $settings['date_x'], $settings['date_y'], $textColorDark);

        // Save generated certificate
        $saveDir = public_path('uploads/certificates/generated');
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }
        $fileName = 'certificate_' . time() . '_' . Str::random(6) . '.png';
        $savePath = $saveDir . '/' . $fileName;

        imagepng($im, $savePath);
        imagedestroy($im);

        return 'uploads/certificates/generated/' . $fileName;
    }
}
