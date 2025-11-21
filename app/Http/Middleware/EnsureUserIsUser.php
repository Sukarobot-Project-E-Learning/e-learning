<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Allow user and instructor roles (instructor can access user area too)
        if (!in_array($user->role, ['user', 'instructor'])) {
            // If user is logged in but not user or instructor, redirect to appropriate login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $message = 'Anda tidak memiliki akses ke area ini.';
            switch ($user->role) {
                case 'admin':
                    $message .= ' Silakan gunakan halaman login admin.';
                    break;
                default:
                    $message .= ' Silakan login dengan akun yang sesuai.';
            }
            
            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}
