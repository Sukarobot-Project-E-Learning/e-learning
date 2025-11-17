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
        // If already logged in as admin, redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login', ['type' => 'admin']);
    }

    /**
     * Show the instructor login form
     */
    public function showInstructorLoginForm()
    {
        // If already logged in as instructor, redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'trainer') {
            return redirect()->route('instructor.dashboard');
        }
        
        return view('auth.login', ['type' => 'instructor']);
    }

    /**
     * Handle admin login request
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

        // Try to authenticate using Laravel's Auth
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai admin.'
                ])->withInput($request->only('email'));
            }
            
            // Check if user is active
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login_at' => now()]);

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    /**
     * Handle instructor login request
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

        // Try to authenticate using Laravel's Auth
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is trainer/instructor
            if ($user->role !== 'trainer') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai instruktur.'
                ])->withInput($request->only('email'));
            }
            
            // Check if user is active
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login_at' => now()]);

            $request->session()->regenerate();

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
        // If already logged in as user, redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'user') {
            return redirect()->route('client.dashboard');
        }
        
        return view('client.layout.page.login.login');
    }

    /**
     * Handle user login request
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

        // Try to authenticate using Laravel's Auth
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is regular user (not admin or trainer)
            if ($user->role !== 'user') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Silakan gunakan halaman login yang sesuai untuk role Anda.'
                ])->withInput($request->only('email'));
            }
            
            // Check if user is active
            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ])->withInput($request->only('email'));
            }

            // Update last login
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login_at' => now()]);

            $request->session()->regenerate();

            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect based on previous role
        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/admin')) {
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
        } elseif (str_contains($previousUrl, '/instructor')) {
            return redirect()->route('instructor.login')->with('success', 'Anda telah berhasil logout.');
        }
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}

