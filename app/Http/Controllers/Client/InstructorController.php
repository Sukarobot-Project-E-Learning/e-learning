<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::join('users', function($join) {
            $join->on('users.email', '=', \Illuminate\Support\Facades\DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
        })
            ->where('data_trainers.status_trainer', 'Aktif')
            ->select(
                'users.name as nama',
                'users.avatar',
                'users.job as jabatan',
                'data_trainers.pengalaman',
                'data_trainers.keahlian',
                'data_trainers.bio as deskripsi', // data_trainers.bio mapped to deskripsi
                'data_trainers.foto'
            )
            ->get()
            ->map(function ($instructor) {
                // Determine photo to use: instructor-specific photo > user avatar > default
                $photo = null;
                if ($instructor->foto) {
                    $photo = asset($instructor->foto);
                } elseif ($instructor->avatar) {
                    $photo = asset($instructor->avatar);
                } else {
                    $photo = 'https://ui-avatars.com/api/?name=' . urlencode($instructor->nama);
                }

                return [
                    'foto' => $photo,
                    'nama' => $instructor->nama,
                    'jabatan' => $instructor->jabatan ?? 'Instructor',
                    'pengalaman' => $instructor->pengalaman ?? '-',
                    'keahlian' => $instructor->keahlian ?? 'General',
                    'deskripsi' => $instructor->deskripsi ?? '-',
                ];
            });

        return view('client.about.instruktur', compact('instructors'));
    }
}
