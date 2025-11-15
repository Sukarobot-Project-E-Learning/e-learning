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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('instructor.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== 'trainer') {
            // If user is logged in but not a trainer, redirect to instructor login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('instructor.login')->with('error', 'Anda tidak memiliki akses sebagai instruktur. Silakan login dengan akun instruktur.');
        }

        return $next($request);
    }
}

