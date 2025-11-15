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
            abort(403, 'Akses ditolak. Hanya instruktur yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}

