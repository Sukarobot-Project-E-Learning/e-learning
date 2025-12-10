<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsInstructor
{
    /**
     * Handle an incoming request.
     * Uses default 'web' guard (shares session with user).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('instructor.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('instructor.login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // STRICT: Only instructor role allowed
        if ($user->role !== 'instructor') {
            $message = 'Anda tidak memiliki akses sebagai instruktur.';
            if ($user->role === 'admin') {
                $message .= ' Silakan gunakan halaman login admin.';
            } elseif ($user->role === 'user') {
                $message .= ' Silakan gunakan halaman login user.';
            }
            
            return redirect()->route('instructor.login')->with('error', $message);
        }

        return $next($request);
    }
}
