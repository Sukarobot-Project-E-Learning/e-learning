<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CloudinaryService
{
    protected string $cloudName;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $uploadUrl;

    public function __construct()
    {
        $this->cloudName = config('cloudinary.cloud_name');
        $this->apiKey = config('cloudinary.api_key');
        $this->apiSecret = config('cloudinary.api_secret');
        $this->uploadUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
    }

    /**
     * Upload a certificate template (blanko) to Cloudinary
     */
    public function uploadTemplate(UploadedFile $file, ?string $publicId = null): array
    {
        $timestamp = time();
        $folder = config('cloudinary.folder', 'certificates/templates');
        
        if (!$publicId) {
            $publicId = $folder . '/' . $timestamp . '_' . Str::random(8);
        }

        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'folder' => '',
        ];

        $signature = $this->generateSignature($params);

        try {
            $response = Http::attach(
                'file', 
                file_get_contents($file->getRealPath()), 
                $file->getClientOriginalName()
            )->post($this->uploadUrl, [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'api_key' => $this->apiKey,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'public_id' => $data['public_id'],
                    'secure_url' => $data['secure_url'],
                    'url' => $data['url'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'format' => $data['format'],
                ];
            }

            Log::error('Cloudinary upload failed', ['response' => $response->json()]);
            return [
                'success' => false,
                'message' => $response->json()['error']['message'] ?? 'Upload failed',
            ];

        } catch (\Exception $e) {
            Log::error('Cloudinary upload exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a template from Cloudinary
     */
    public function deleteTemplate(string $publicId): bool
    {
        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        $signature = $this->generateSignature($params);

        try {
            $response = Http::post(
                "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy",
                [
                    'public_id' => $publicId,
                    'timestamp' => $timestamp,
                    'api_key' => $this->apiKey,
                    'signature' => $signature,
                ]
            );

            return $response->successful() && ($response->json()['result'] ?? '') === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Generate certificate image URL with text overlay transformations
     * 
     * Fonts:
     * - Nama Penerima: Lobster (Google Font)
     * - Nomor, Deskripsi, Tanggal: Lato (Google Font)
     * 
     * Positioning: Uses g_center with offset from image center
     */
    public function generateCertificateUrl(
        string $publicId,
        string $recipientName,
        string $certificateNumber,
        ?string $description,
        string $issuedDate,
        array $settings,
        int $imageWidth = 1920,
        int $imageHeight = 1357
    ): string {
        $cloudName = $this->cloudName;
        $baseUrl = "https://res.cloudinary.com/{$cloudName}/image/upload";

        $transformations = [];
        
        // Image center point
        $centerX = $imageWidth / 2;
        $centerY = $imageHeight / 2;

        // 1. Name overlay - LOBSTER font (Google Font)
        $namePixelX = ($settings['name_x'] / 100) * $imageWidth;
        $namePixelY = ($settings['name_y'] / 100) * $imageHeight;
        $nameOffsetX = (int) round($namePixelX - $centerX);
        $nameOffsetY = (int) round($namePixelY - $centerY);
        $nameFontSize = (int) (($settings['name_font_size'] ?? 35) * 2.2);
        $nameEncoded = $this->encodeTextForUrl($recipientName);
        // Lobster font for name
        $transformations[] = "l_text:Lobster_" . $nameFontSize . ":" . $nameEncoded . ",co_rgb:1a1a1a,g_center,x_" . $nameOffsetX . ",y_" . $nameOffsetY;

        // 2. Certificate number overlay - LATO font (Google Font)
        $numberPixelX = ($settings['number_x'] / 100) * $imageWidth;
        $numberPixelY = ($settings['number_y'] / 100) * $imageHeight;
        $numberOffsetX = (int) round($numberPixelX - $centerX);
        $numberOffsetY = (int) round($numberPixelY - $centerY);
        $numberFontSize = (int) (($settings['number_font_size'] ?? 11) * 2.2);
        $numberText = "No: " . $certificateNumber;
        $numberEncoded = $this->encodeTextForUrl($numberText);
        // Lato font for number
        $transformations[] = "l_text:Lato_" . $numberFontSize . ":" . $numberEncoded . ",co_rgb:1a1a1a,g_center,x_" . $numberOffsetX . ",y_" . $numberOffsetY;

        // 3. Description overlay - LATO font (Google Font)
        if ($description) {
            $descPixelX = ($settings['desc_x'] / 100) * $imageWidth;
            $descPixelY = ($settings['desc_y'] / 100) * $imageHeight;
            $descOffsetX = (int) round($descPixelX - $centerX);
            $descOffsetY = (int) round($descPixelY - $centerY);
            $descFontSize = (int) (($settings['desc_font_size'] ?? 13) * 2.2);
            $descEncoded = $this->encodeTextForUrl($description);
            // Lato font for description with text wrapping (c_fit for centered text alignment)
            $wrapWidth = (int) ($imageWidth * 0.65);
            $transformations[] = "l_text:Lato_" . $descFontSize . ":" . $descEncoded . ",co_rgb:1a1a1a,g_center,x_" . $descOffsetX . ",y_" . $descOffsetY . ",w_" . $wrapWidth . ",c_fit";
        }

        // 4. Date overlay - LATO font (Google Font)
        $datePixelX = ($settings['date_x'] / 100) * $imageWidth;
        $datePixelY = ($settings['date_y'] / 100) * $imageHeight;
        $dateOffsetX = (int) round($datePixelX - $centerX);
        $dateOffsetY = (int) round($datePixelY - $centerY);
        $dateFontSize = (int) (($settings['date_font_size'] ?? 13) * 2.2);
        $dateEncoded = $this->encodeTextForUrl($issuedDate);
        // Lato font for date
        $transformations[] = "l_text:Lato_" . $dateFontSize . ":" . $dateEncoded . ",co_rgb:1a1a1a,g_center,x_" . $dateOffsetX . ",y_" . $dateOffsetY;

        // Combine all transformations
        $transformString = implode('/', $transformations);

        return "{$baseUrl}/{$transformString}/{$publicId}";
    }

    /**
     * Generate download URL from Cloudinary image (downloads as image, not PDF)
     * Cloudinary PDF conversion has limitations, so we download high-quality image instead
     */
    public function generateDownloadUrl(string $imageUrl): string
    {
        // Add fl_attachment to force download as image
        return preg_replace('/\/upload\//', '/upload/fl_attachment/', $imageUrl);
    }
    /**
     * Generate signature for Cloudinary API authentication
     */
    protected function generateSignature(array $params): string
    {
        $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
        ksort($params);

        $signatureString = '';
        foreach ($params as $key => $value) {
            $signatureString .= "{$key}={$value}&";
        }
        $signatureString = rtrim($signatureString, '&');
        $signatureString .= $this->apiSecret;

        return sha1($signatureString);
    }

    /**
     * Encode text for Cloudinary URL
     */
    protected function encodeTextForUrl(string $text): string
    {
        $text = str_replace(
            ['/', '\\', '?', '#', '&', '=', '%', ',', ':'],
            [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
            $text
        );
        return rawurlencode($text);
    }

    /**
     * Format date in Indonesian
     */
    public function formatIndonesianDate($date): string
    {
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format('d') . ' ' . ($months[$date->month] ?? $date->format('F')) . ' ' . $date->format('Y');
    }

    /**
     * Check if Cloudinary is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
    }
}
