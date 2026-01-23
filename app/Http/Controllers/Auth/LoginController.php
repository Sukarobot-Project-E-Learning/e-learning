<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showAdminLoginForm()
    {
        // If already logged in as admin (with admin guard), redirect to dashboard
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login', ['type' => 'admin']);
    }

    /**
     * Show the instructor login form
     */
    public function showInstructorLoginForm()
    {
        // If already logged in as instructor (with web guard), redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }
        
        return view('auth.login', ['type' => 'instructor']);
    }

    /**
     * Handle admin login request
     * Uses 'admin' guard for separate session
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // First, find the user by email to check role before authentication
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.'
            ])->withInput($request->only('email'));
        }

        // Check if user has admin role
        if ($user->role !== 'admin') {
            return back()->withErrors([
                'email' => 'Anda tidak memiliki akses sebagai admin. Silakan gunakan halaman login yang sesuai untuk role Anda.'
            ])->withInput($request->only('email'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ])->withInput($request->only('email'));
        }

        // Try to authenticate using admin guard
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $authenticatedUser = Auth::guard('admin')->user();
            
            // Double check role after authentication
            if ($authenticatedUser->role !== 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai admin.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $authenticatedUser->id)
                ->update(['last_login_at' => now()]);

            // Don't regenerate session to preserve other guard sessions
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    /**
     * Handle instructor login request
     * Uses default 'web' guard (shares session with user)
     */
    public function instructorLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // First, find the user by email to check role before authentication
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.'
            ])->withInput($request->only('email'));
        }

        // STRICT: Only allow instructor role, no other roles
        if ($user->role !== 'instructor') {
            $roleMessage = '';
            switch ($user->role) {
                case 'admin':
                    $roleMessage = 'Anda adalah admin. Silakan gunakan halaman login admin.';
                    break;
                case 'user':
                    // Check if they have an approved application but role wasn't updated
                    $approvedApp = DB::table('instructor_applications')
                        ->where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->first();
                    
                    if ($approvedApp) {
                        // Fix the role automatically
                        DB::table('users')->where('id', $user->id)->update(['role' => 'instructor']);
                        // Continue to login logic by breaking out of this checking block? 
                        // Actually, we need to bypass the return error.
                        // Let's modify the flow.
                        $user->role = 'instructor'; // Memory update
                        break; // Break switch, but we are still inside the if ($user->role !== 'instructor') block?
                    }

                    $roleMessage = 'Anda adalah user biasa. Silakan gunakan halaman login user.';
                    break;
                default:
                    $roleMessage = 'Anda tidak memiliki akses sebagai instruktur.';
            }
            
            // If we didn't fix the role, return error
            if ($user->role !== 'instructor') {
                return back()->withErrors([
                    'email' => $roleMessage
                ])->withInput($request->only('email'));
            }
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ])->withInput($request->only('email'));
        }

        // Try to authenticate using default web guard
        if (Auth::attempt($credentials, $remember)) {
            $authenticatedUser = Auth::user();
            
            // Double check role after authentication
            if ($authenticatedUser->role !== 'instructor') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai instruktur.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $authenticatedUser->id)
                ->update(['last_login_at' => now()]);

            // Don't regenerate session to preserve other guard sessions
            return redirect()->intended(route('instructor.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    /**
     * Show the user login form
     */
    public function showUserLoginForm()
    {
        // If already logged in as user or instructor (with web guard), redirect to dashboard
        if (Auth::check() && in_array(Auth::user()->role, ['user', 'instructor'])) {
            return redirect()->route('client.dashboard');
        }
        
        return view('client.login.login');
    }

    /**
     * Handle user login request
     * Uses default 'web' guard (shares session with instructor)
     */
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // First, find the user by email to check role before authentication
        $user = DB::table('users')->where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.'
            ])->withInput($request->only('email'));
        }

        // Allow user and instructor roles (instructor can login as user too)
        if (!in_array($user->role, ['user', 'instructor'])) {
            $roleMessage = '';
            switch ($user->role) {
                case 'admin':
                    $roleMessage = 'Anda adalah admin. Silakan gunakan halaman login admin.';
                    break;
                default:
                    $roleMessage = 'Silakan gunakan halaman login yang sesuai untuk role Anda.';
            }
            
            return back()->withErrors([
                'email' => $roleMessage
            ])->withInput($request->only('email'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ])->withInput($request->only('email'));
        }

        // Try to authenticate using default web guard
        if (Auth::attempt($credentials, $remember)) {
            $authenticatedUser = Auth::user();
            
            // Allow user and instructor roles
            if (!in_array($authenticatedUser->role, ['user', 'instructor'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Silakan gunakan halaman login yang sesuai untuk role Anda.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $authenticatedUser->id)
                ->update(['last_login_at' => now()]);

            // Don't regenerate session to preserve other guard sessions
            return redirect()->intended(route('client.dashboard.program'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     * Determines which guard to logout based on the referrer URL
     * IMPORTANT: Only regenerate token when ALL guards are logged out
     */
    public function logout(Request $request)
    {
        $previousUrl = url()->previous();
        
        // If coming from admin area, logout admin guard only
        if (str_contains($previousUrl, '/admin')) {
            Auth::guard('admin')->logout();
            
            // Only regenerate token if web guard is also logged out
            if (!Auth::check()) {
                $request->session()->regenerateToken();
            }
            
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout dari admin.');
        }
        
        // If coming from instructor area, logout web guard only
        if (str_contains($previousUrl, '/instructor')) {
            Auth::logout();
            
            // Only regenerate token if admin guard is also logged out
            if (!Auth::guard('admin')->check()) {
                $request->session()->regenerateToken();
            }
            
            return redirect()->route('instructor.login')->with('success', 'Anda telah berhasil logout.');
        }
        
        // Default: logout web guard (user/instructor) only
        Auth::logout();
        
        // Only regenerate token if admin guard is also logged out
        if (!Auth::guard('admin')->check()) {
            $request->session()->regenerateToken();
        }
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
