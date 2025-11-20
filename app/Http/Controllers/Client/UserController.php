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
        return view('client.layout.page.dashboard', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Logic to update user profile
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maksimal ukuran 2MB
        ]);
    }
}
