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
        // Ensure config is loaded
        config([
            'services.google.client_id' => env('GOOGLE_CLIENT_ID'),
            'services.google.client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'services.google.redirect' => env('GOOGLE_REDIRECT_URI'),
        ]);
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
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
                } elseif ($user->role === 'trainer' || $user->role === 'instructor') {
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
            return redirect()->route('login')->withErrors([
                'email' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.'
            ]);
        }
    }
}

