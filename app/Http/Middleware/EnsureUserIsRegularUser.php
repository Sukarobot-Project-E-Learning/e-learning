<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsRegularUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();
        
        // Allow 'user' and 'instructor' roles
        // Admin is NOT allowed to access user dashboard
        if (in_array($user->role, ['user', 'instructor'])) {
            return $next($request);
        }

        // Redirect admin to their dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak memiliki akses ke dashboard user.');
        }

        // For any other role, redirect to login
        auth()->logout();
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
