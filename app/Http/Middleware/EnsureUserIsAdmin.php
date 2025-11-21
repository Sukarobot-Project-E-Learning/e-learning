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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // STRICT: Only admin role allowed
        if ($user->role !== 'admin') {
            // If user is logged in but not an admin, redirect to appropriate login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $message = 'Anda tidak memiliki akses sebagai admin.';
            switch ($user->role) {
                case 'instructor':
                    $message .= ' Silakan gunakan halaman login instruktur.';
                    break;
                case 'user':
                    $message .= ' Silakan gunakan halaman login user.';
                    break;
                default:
                    $message .= ' Silakan login dengan akun admin.';
            }
            
            return redirect()->route('admin.login')->with('error', $message);
        }

        return $next($request);
    }
}

