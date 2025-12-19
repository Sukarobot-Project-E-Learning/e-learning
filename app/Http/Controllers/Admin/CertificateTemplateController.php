<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function index()
    {
        $templates = DB::table('certificate_templates')
            ->join('data_programs', 'certificate_templates.program_id', '=', 'data_programs.id')
            ->select('certificate_templates.*', 'data_programs.program as program_name')
            ->orderBy('certificate_templates.created_at', 'desc')
            ->paginate(10);

        return view('admin.certificates.templates.index', compact('templates'));
    }

    public function create()
    {
        $programs = DB::table('data_programs')->select('id', 'program')->get();
        
        // List fonts from public/assets/fonts
        $fonts = [];
        $fontPath = public_path('assets/fonts');
        if (is_dir($fontPath)) {
            $files = scandir($fontPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'ttf') {
                    $fonts[] = $file;
                }
            }
        }
        
        return view('admin.certificates.templates.create', compact('programs', 'fonts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:data_programs,id',
            'background_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            // Coordinates
            'name_x' => 'required|integer', 'name_y' => 'required|integer',
            'number_x' => 'required|integer', 'number_y' => 'required|integer',
            'desc_x' => 'required|integer', 'desc_y' => 'required|integer',
            'date_x' => 'required|integer', 'date_y' => 'required|integer',
            // Fonts
            'name_font' => 'required|string',
            'number_font' => 'required|string',
            'desc_font' => 'required|string',
        ]);

        $imagePath = $request->file('background_image')->store('certificates/templates', 'public');

        DB::table('certificate_templates')->insert([
            'program_id' => $request->program_id,
            'background_image' => $imagePath,
            'description' => $request->description,
            'name_position' => json_encode(['x' => $request->name_x, 'y' => $request->name_y, 'color' => '#000000', 'font_size' => 38, 'font' => $request->name_font]),
            'number_position' => json_encode(['x' => $request->number_x, 'y' => $request->number_y, 'color' => '#000000', 'font_size' => 12, 'font' => $request->number_font]),
            'description_position' => json_encode(['x' => $request->desc_x, 'y' => $request->desc_y, 'color' => '#000000', 'font_size' => 16, 'font' => $request->desc_font]),
            'date_position' => json_encode(['x' => $request->date_x, 'y' => $request->date_y, 'color' => '#000000', 'font_size' => 16, 'font' => $request->desc_font]), // Use desc font for date
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.certificates.templates.index')->with('success', 'Template sertifikat berhasil dibuat');
    }

    public function edit($id)
    {
        $template = DB::table('certificate_templates')->where('id', $id)->first();
        if (!$template) return redirect()->back()->with('error', 'Template tidak ditemukan');

        $programs = DB::table('data_programs')->select('id', 'program')->get();
        
        // List fonts
        $fonts = [];
        $fontPath = public_path('assets/fonts');
        if (is_dir($fontPath)) {
            $files = scandir($fontPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'ttf') {
                    $fonts[] = $file;
                }
            }
        }
        
        // Decode JSON positions
        $template->name_pos = json_decode($template->name_position);
        $template->number_pos = json_decode($template->number_position);
        $template->desc_pos = json_decode($template->description_position);
        $template->date_pos = json_decode($template->date_position);

        return view('admin.certificates.templates.edit', compact('template', 'programs', 'fonts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'program_id' => 'required|exists:data_programs,id',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'name_x' => 'required|integer', 'name_y' => 'required|integer',
            'number_x' => 'required|integer', 'number_y' => 'required|integer',
            'desc_x' => 'required|integer', 'desc_y' => 'required|integer',
            'date_x' => 'required|integer', 'date_y' => 'required|integer',
            'name_font' => 'required|string',
            'number_font' => 'required|string',
            'desc_font' => 'required|string',
        ]);

        $data = [
            'program_id' => $request->program_id,
            'description' => $request->description,
            'name_position' => json_encode(['x' => $request->name_x, 'y' => $request->name_y, 'color' => '#000000', 'font_size' => 38, 'font' => $request->name_font]),
            'number_position' => json_encode(['x' => $request->number_x, 'y' => $request->number_y, 'color' => '#000000', 'font_size' => 12, 'font' => $request->number_font]),
            'description_position' => json_encode(['x' => $request->desc_x, 'y' => $request->desc_y, 'color' => '#000000', 'font_size' => 16, 'font' => $request->desc_font]),
            'date_position' => json_encode(['x' => $request->date_x, 'y' => $request->date_y, 'color' => '#000000', 'font_size' => 16, 'font' => $request->desc_font]),
            'updated_at' => now(),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('certificates/templates', 'public');
        }

        DB::table('certificate_templates')->where('id', $id)->update($data);

        return redirect()->route('admin.certificates.templates.index')->with('success', 'Template sertifikat berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('certificate_templates')->where('id', $id)->delete();
        return redirect()->route('admin.certificates.templates.index')->with('success', 'Template sertifikat berhasil dihapus');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'name_x' => 'required|integer', 'name_y' => 'required|integer',
            'number_x' => 'required|integer', 'number_y' => 'required|integer',
            'desc_x' => 'required|integer', 'desc_y' => 'required|integer',
            'date_x' => 'required|integer', 'date_y' => 'required|integer',
            'name_font' => 'required|string',
            'number_font' => 'required|string',
            'desc_font' => 'required|string',
        ]);

        // Handle Image
        if ($request->hasFile('background_image')) {
            $imagePath = $request->file('background_image')->path();
            $ext = $request->file('background_image')->extension();
        } elseif ($request->current_bg) {
            $imagePath = storage_path('app/public/' . $request->current_bg);
            $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
        } else {
            return response()->json(['error' => 'Background image required'], 400);
        }

        if (!file_exists($imagePath)) {
             return response()->json(['error' => 'Image file not found'], 404);
        }

        // Create Image Resource
        if ($ext == 'png') {
            $image = imagecreatefrompng($imagePath);
        } else {
            $image = imagecreatefromjpeg($imagePath); // Assume JPG/JPEG
        }

        // Colors
        $black = imagecolorallocate($image, 0, 0, 0);

        // Dummy Data
        $certNumber = "No: 001/B-1/PT.STG/XII/" . date('Y');
        $studentName = "John Doe (Preview)";
        $descText = $request->description;
        $dateStr = "01 Desember " . date('Y');

        // Helper to center text (Same as ProgramProofController)
        $addText = function($img, $size, $angle, $x, $y, $color, $fontFile, $text) {
            $fontPath = public_path('assets/fonts/' . $fontFile);
            if (!file_exists($fontPath) || empty($fontFile)) {
                $fontPath = 'C:\Windows\Fonts\arial.ttf';
            }
            $bbox = imagettfbbox($size, $angle, $fontPath, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $centeredX = $x - ($textWidth / 2);
            imagettftext($img, $size, $angle, $centeredX, $y, $color, $fontPath, $text);
        };

        // Add Text
        $addText($image, 12, 0, $request->number_x, $request->number_y, $black, $request->number_font, $certNumber);
        $addText($image, 38, 0, $request->name_x, $request->name_y, $black, $request->name_font, $studentName);
        $addText($image, 16, 0, $request->desc_x, $request->desc_y, $black, $request->desc_font, $descText);
        $addText($image, 16, 0, $request->date_x, $request->date_y, $black, $request->desc_font, $dateStr);

        // Output to buffer
        ob_start();
        imagejpeg($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        return response()->json([
            'image' => 'data:image/jpeg;base64,' . base64_encode($imageData)
        ]);
    }
}
