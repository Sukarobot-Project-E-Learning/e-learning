<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        // TODO: Get current instructor data
        // $instructor = auth()->user();
        
        return view('instructor.profile.edit');
    }

    /**
     * Update the profile in storage.
     */
    public function update(Request $request)
    {
        // TODO: Add validation and update logic here
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email,' . auth()->id(),
        //     'phone' => 'nullable|string|max:20',
        //     'job' => 'nullable|string|max:255',
        //     'experience' => 'nullable|string|max:255',
        //     'expertise' => 'nullable|string|max:255',
        //     'photo' => 'nullable|image|max:2048',
        // ]);
        
        // Update logic here
        
        return redirect()->route('instructor.profile.edit')->with('success', 'Profile berhasil diupdate');
    }
}

