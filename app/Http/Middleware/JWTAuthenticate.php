<?php

namespace App\Http\Middleware;

use App\Services\JWTService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticate
{
    protected JWTService $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
            ], 401);
        }

        $decoded = $this->jwtService->decodeToken($token);

        if (!$decoded) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah kadaluarsa.',
            ], 401);
        }

        // Get user from database
        $user = DB::table('users')->where('id', $decoded->sub)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif.',
            ], 403);
        }

        // Set user to request for access in controllers
        $request->merge(['jwt_user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
