<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username|alpha_dash',
                'phone' => 'required|string|max:20|regex:/^[0-9]+$/|unique:users,phone',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'Nama lengkap wajib diisi',
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username sudah digunakan',
                'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash dan underscore',
                'phone.required' => 'Nomor HP wajib diisi',
                'phone.regex' => 'Nomor HP hanya boleh angka',
                'phone.unique' => 'Nomor HP sudah terdaftar',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Normalize email to lowercase
            $normalizedEmail = strtolower(trim($request->email));

            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'username' => strtolower(trim($request->username)),
                'phone' => $request->phone,
                'email' => $normalizedEmail,
                'password' => $request->password, // Auto-hashed by model casts
                'role' => 'user',
                'is_active' => true,
            ]);

            // Login user dengan session (untuk web-based registration)
            \Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Selamat datang di Sukarobot 🚀',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'avatar' => $user->avatar,
                    ],
                    'redirect_url' => '/dashboard'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari user berdasarkan email
            $user = User::where('email', $request->email)->first();

            // Cek apakah user ada dan password benar
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // Cek apakah user aktif
            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi admin.'
                ], 403);
            }

            // Update last login
            $user->update(['last_login_at' => now()]);

            // Generate JWT token
            $jwtService = app(\App\Services\JWTService::class);
            $accessToken = $jwtService->generateTokenForUser($user);
            $refreshToken = $jwtService->generateRefreshToken(['sub' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil! Selamat datang kembali 👋',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'avatar' => $user->avatar,
                    ],
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.expires_in', 86400),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (for JWT, client should discard token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // JWT is stateless, so we just return success
        // Client should remove token from storage
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Sampai jumpa! 👋'
        ], 200);
    }

    /**
     * Refresh access token using refresh token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string',
            ], [
                'refresh_token.required' => 'Refresh token wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jwtService = app(\App\Services\JWTService::class);
            $decoded = $jwtService->validateRefreshToken($request->refresh_token);

            if (!$decoded) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refresh token tidak valid atau sudah kadaluarsa'
                ], 401);
            }

            // Get user
            $user = User::find($decoded->sub);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif'
                ], 403);
            }

            // Generate new access token
            $accessToken = $jwtService->generateTokenForUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Token berhasil diperbarui',
                'data' => [
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.expires_in', 86400),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Data profil berhasil diambil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'avatar' => $user->avatar,
                        'is_active' => $user->is_active,
                        'last_login_at' => $user->last_login_at,
                        'created_at' => $user->created_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

