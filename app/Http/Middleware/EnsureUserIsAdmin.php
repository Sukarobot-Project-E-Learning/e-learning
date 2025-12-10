<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * Uses 'admin' guard for separate session from user/instructor.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if logged in with admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('admin')->user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // STRICT: Only admin role allowed
        if ($user->role !== 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        return $next($request);
    }
}
