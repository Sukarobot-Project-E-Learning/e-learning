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
        $user = auth()->user();
        $trainer = \DB::table('data_trainers')
            ->where('email', $user->email)
            ->first();

        return view('instructor.profile.edit', compact('trainer'));
    }

    /**
     * Update the profile in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validation sesuai dengan kolom di data_trainers
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:255',
            'pengalaman' => 'nullable|string',
            'keahlian' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Update trainer data
        $trainer = \DB::table('data_trainers')
            ->where('email', $user->email)
            ->first();

        if ($trainer) {
            // Handle photo upload if provided
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('trainer-photos', 'public');
                $validated['foto'] = $path;
            }

            \DB::table('data_trainers')
                ->where('id', $trainer->id)
                ->update($validated);
        }

        return redirect()->route('instructor.profile.edit')
            ->with('success', 'Profile berhasil diupdate');
    }
}
