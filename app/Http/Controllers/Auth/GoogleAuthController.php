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
        // Use config() instead of env() - works with cached config on production
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect') ?: route('google.callback');
        
        // Validate credentials
        if (empty($clientId) || empty($clientSecret)) {
            \Log::error('Google OAuth Config Missing', [
                'client_id' => $clientId ? 'SET' : 'MISSING',
                'client_secret' => $clientSecret ? 'SET' : 'MISSING',
            ]);
            return redirect()->route('login')->withErrors([
                'email' => 'Konfigurasi Google OAuth belum lengkap. Pastikan GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET sudah diisi di file .env dan jalankan php artisan config:cache'
            ]);
        }
        
        try {
            \Log::info('Google OAuth Redirect', [
                'client_id' => substr($clientId, 0, 20) . '...',
                'redirect_uri' => $redirectUri,
            ]);

            return Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->stateless()
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Terjadi kesalahan saat mengakses Google OAuth: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Check for error from Google
            if ($request->has('error')) {
                $errorMessage = $request->get('error_description', $request->get('error', 'Unknown error'));
                \Log::error('Google OAuth Error from Google', ['error' => $errorMessage]);
                return redirect()->route('login')->withErrors([
                    'email' => 'Google OAuth Error: ' . $errorMessage
                ]);
            }

            // Check if code is present
            if (!$request->has('code')) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Authorization code tidak ditemukan dari Google.'
                ]);
            }

            // Get redirect URI from config (works with cached config)
            $redirectUri = config('services.google.redirect') ?: route('google.callback');

            // Debug log
            \Log::info('Google OAuth Callback', [
                'client_id' => config('services.google.client_id') ? 'SET' : 'MISSING',
                'redirect' => $redirectUri,
                'has_code' => $request->has('code'),
            ]);

            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->stateless()
                ->user();
                
            // Normalize email to lowercase for consistent matching
            $normalizedEmail = strtolower(trim($googleUser->email));
            
            // Check if user exists by email
            $user = DB::table('users')->where('email', $normalizedEmail)->first();
            
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
                    return redirect()->route('client.dashboard.program');
                } else {
                    return redirect()->route('client.dashboard.program');
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
                    'email' => $normalizedEmail, // Use normalized email
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
                // User data is now stored only in users table (data_siswas removed)
                
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

