<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    /**
     * Base URL for Indonesia region data API
     */
    private const BASE_URL = 'https://gilarya.github.io/data-indonesia';

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            $provinces = Cache::remember('provinces', 3600, function () {
                $response = Http::get(self::BASE_URL . '/provinsi.json');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    /**
     * Get kabupaten/kota by province ID
     */
    public function getKabupatenKota($provinceId)
    {
        try {
            $cacheKey = "kabupaten_{$provinceId}";
            
            $kabupatenKota = Cache::remember($cacheKey, 3600, function () use ($provinceId) {
                // Try kabupaten first
                $response = Http::get(self::BASE_URL . "/kabupaten/{$provinceId}.json");
                
                if ($response->successful()) {
                    $kabupaten = $response->json();
                } else {
                    // Try kota if kabupaten fails
                    $response = Http::get(self::BASE_URL . "/kota/{$provinceId}.json");
                    if ($response->successful()) {
                        $kabupaten = $response->json();
                    } else {
                        return [];
                    }
                }
                
                return $kabupaten;
            });

            return response()->json($kabupatenKota);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch kabupaten/kota'], 500);
        }
    }

    /**
     * Get kecamatan by kabupaten ID
     */
    public function getKecamatan($kabupatenId)
    {
        try {
            $cacheKey = "kecamatan_{$kabupatenId}";
            
            $kecamatan = Cache::remember($cacheKey, 3600, function () use ($kabupatenId) {
                $response = Http::get(self::BASE_URL . "/kecamatan/{$kabupatenId}.json");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($kecamatan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch kecamatan'], 500);
        }
    }

    /**
     * Get kelurahan by kecamatan ID
     */
    public function getKelurahan($kecamatanId)
    {
        try {
            $cacheKey = "kelurahan_{$kecamatanId}";
            
            $kelurahan = Cache::remember($cacheKey, 3600, function () use ($kecamatanId) {
                $response = Http::get(self::BASE_URL . "/kelurahan/{$kecamatanId}.json");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return [];
            });

            return response()->json($kelurahan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch kelurahan'], 500);
        }
    }
}

