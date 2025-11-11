<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $broadcasts = [
            [
                'id' => 1,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 2,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 3,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 4,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 5,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 6,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
            [
                'id' => 7,
                'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin,...'
            ],
        ];

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.broadcasts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation
        // TODO: Send broadcast message
        // TODO: Save to database

        return redirect()->route('elearning.admin.broadcasts.index')
            ->with('success', 'Broadcast berhasil dikirim');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dummy data untuk sementara
        $broadcast = [
            'id' => $id,
            'message' => 'Selamat pagi, peserta Workshop Branding! Jangan lupa acara pengenalan Pelatihan akan diselenggarakan pada hari Senin, 15 Jul 2024, pukul 14.00 WIB yang dilakukan secara online. Ayo datang dan temukan kegiatan seru yang sesuai dengan minatmu!'
        ];

        return view('admin.broadcasts.edit', compact('broadcast'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation
        // TODO: Update in database

        return redirect()->route('elearning.admin.broadcasts.index')
            ->with('success', 'Broadcast berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database

        return redirect()->route('elearning.admin.broadcasts.index')
            ->with('success', 'Broadcast berhasil dihapus');
    }
}

