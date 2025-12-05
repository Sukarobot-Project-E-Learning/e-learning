<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Controller methods for user-related actions can be added here
    public function profile()
    {
        // Logic to display user profile
        $user = Auth::user(); // ambil data user dari database
        return view('client.dashboard.profile', compact('user'));
    }

    public function program()
    {
        return view('client.dashboard.program');
    }

    public function certificate()
    {
        return view('client.dashboard.certificate');
    }

    public function transaction()
    {
        return view('client.dashboard.transaction');
    }

    public function voucher()
    {
        return view('client.dashboard.voucher');
    }

    public function updateProfile(Request $request)
    {
        // Logic to update user profile
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maksimal ukuran 2MB
        ]);
    }
}
