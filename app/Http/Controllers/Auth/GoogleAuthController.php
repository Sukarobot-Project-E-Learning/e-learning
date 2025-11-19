<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Get credentials from env
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $redirectUri = env('GOOGLE_REDIRECT_URI');
        
        // Debug: Check if env is loaded (remove after fixing)
        if (empty($clientId) || empty($clientSecret)) {
            // Try to get from config as fallback
            $clientId = $clientId ?: config('services.google.client_id');
            $clientSecret = $clientSecret ?: config('services.google.client_secret');
            $redirectUri = $redirectUri ?: config('services.google.redirect');
        }
        
        // Validate credentials
        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Konfigurasi Google OAuth belum lengkap. Pastikan GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET sudah diisi di file .env'
            ]);
        }
        
        // Build redirect URI if not provided
        if (empty($redirectUri)) {
            $redirectUri = route('google.callback');
        }
        
        // Ensure config is loaded
        config([
            'services.google.client_id' => $clientId,
            'services.google.client_secret' => $clientSecret,
            'services.google.redirect' => $redirectUri,
        ]);
        
        try {
            // Debug log (remove after fixing)
            \Log::info('Google OAuth Redirect', [
                'client_id' => config('services.google.client_id'),
                'redirect_uri' => $redirectUri,
            ]);

            return Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->redirect();
        } catch (\Exception $e) {
            // Log error
            \Log::error('Google OAuth Redirect Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Terjadi kesalahan saat mengakses Google OAuth: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get redirect URI for callback
            $redirectUri = env('GOOGLE_REDIRECT_URI') ?: route('google.callback');

            // Ensure config is loaded for callback too
            config([
                'services.google.client_id' => env('GOOGLE_CLIENT_ID'),
                'services.google.client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'services.google.redirect' => $redirectUri,
            ]);

            // Debug log (remove after fixing)
            \Log::info('Google OAuth Config', [
                'client_id' => config('services.google.client_id'),
                'redirect' => config('services.google.redirect'),
            ]);

            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            // Check if user exists by email
            $user = DB::table('users')->where('email', $googleUser->email)->first();
            
            if ($user) {
                // User exists - Login
                // Check if user is active
                if (isset($user->is_active) && !$user->is_active) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                    ]);
                }
                
                // Update provider info if not set
                $userProvider = $user->provider ?? null;
                if (!$userProvider || $userProvider !== 'google') {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'provider' => 'google',
                            'provider_id' => $googleUser->id,
                            'avatar' => $googleUser->avatar ?? $user->avatar,
                            'updated_at' => now(),
                        ]);
                }
                
                // Update avatar if changed
                if ($googleUser->avatar && $user->avatar !== $googleUser->avatar) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'avatar' => $googleUser->avatar,
                            'updated_at' => now(),
                        ]);
                }
                
                // Update last login
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_login_at' => now()]);
                
                // Login user
                Auth::loginUsingId($user->id);
                
                // Redirect based on role
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'instructor') {
                    return redirect()->route('instructor.dashboard');
                } else {
                    return redirect()->route('client.dashboard');
                }
            } else {
                // User doesn't exist - Register
                // Generate username from email
                $username = Str::before($googleUser->email, '@');
                $username = Str::slug($username);
                
                // Check if username exists, if yes add random number
                $existingUsername = DB::table('users')->where('username', $username)->exists();
                if ($existingUsername) {
                    $username = $username . '_' . rand(1000, 9999);
                }
                
                // Create new user
                $userId = DB::table('users')->insertGetId([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'username' => $username,
                    'password' => Hash::make(Str::random(32)), // Random password since using Google
                    'provider' => 'google',
                    'provider_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'role' => 'user',
                    'is_active' => 1,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Also insert to data_siswas if not exists
                $existingSiswa = DB::table('data_siswas')->where('email', $googleUser->email)->first();
                if (!$existingSiswa) {
                    DB::table('data_siswas')->insert([
                        'nama_lengkap' => $googleUser->name,
                        'email' => $googleUser->email,
                        'status_siswa' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Login the newly created user
                Auth::loginUsingId($userId);
                
                return redirect()->route('client.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Sukarobot 🚀');
            }
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Show detailed error in development, generic in production
            $errorMessage = config('app.debug')
                ? 'Google OAuth Error: ' . $e->getMessage()
                : 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.';

            return redirect()->route('login')->withErrors([
                'email' => $errorMessage
            ]);
        }
    }
}

