<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get popular programs (top rated and most enrolled)
        $popularPrograms = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name'
            )
            ->where('data_programs.status', 'published')
            ->orderBy('data_programs.rating', 'desc')
            ->orderBy('data_programs.enrolled_count', 'desc')
            ->limit(8)
            ->get();

        // Get instructors for home page (top rated by experience limit 6)
        $instructors = DB::table('data_trainers')
            ->join('users', function($join) {
                $join->on('users.email', '=', DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
            })
            ->select(
                'users.name as nama',
                'users.avatar',
                'users.job as jabatan',
                'data_trainers.pengalaman',
                'data_trainers.keahlian',
                'data_trainers.bio as deskripsi',
                'data_trainers.foto'
            )
            ->where('data_trainers.status_trainer', 'Aktif')
            ->orderBy('data_trainers.pengalaman', 'desc')
            ->limit(6)
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

                return (object) [ // Return as object to match blade syntax
                    'foto' => $photo,
                    'nama' => $instructor->nama,
                    'jabatan' => $instructor->jabatan ?? 'Instructor',
                    'pengalaman' => $instructor->pengalaman ?? '-',
                    'keahlian' => $instructor->keahlian ?? 'General',
                    'deskripsi' => $instructor->deskripsi ?? '-',
                ];
            });
        // $instructors = \App\Models\Instructor::join('users', function($join) {
        //     $join->on('users.email', '=', \Illuminate\Support\Facades\DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
        // })
        //     ->where('data_trainers.status_trainer', 'Aktif')
        //     ->select(
        //         'users.name as nama',
        //         'users.avatar',
        //         'users.job as jabatan',
        //         'data_trainers.pengalaman',
        //         'data_trainers.keahlian',
        //         'data_trainers.bio as deskripsi',
        //         'data_trainers.foto'
        //     )
        //     ->inRandomOrder()
        //     ->limit(6)
        //     ->get()
        //     ->map(function ($instructor) {
        //         // Determine photo to use: instructor-specific photo > user avatar > default
        //         $photo = null;
        //         if ($instructor->foto) {
        //             $photo = asset($instructor->foto);
        //         } elseif ($instructor->avatar) {
        //             $photo = asset($instructor->avatar);
        //         } else {
        //             $photo = 'https://ui-avatars.com/api/?name=' . urlencode($instructor->nama);
        //         }

        //         return (object) [ // Return as object to match blade syntax
        //             'foto' => $photo,
        //             'nama' => $instructor->nama,
        //             'jabatan' => $instructor->jabatan ?? 'Instructor',
        //             'pengalaman' => $instructor->pengalaman ?? '-',
        //             'keahlian' => $instructor->keahlian ?? 'General',
        //             'deskripsi' => $instructor->deskripsi ?? '-',
        //         ];
        //     });

        // Transform programs data
        $popularPrograms = $popularPrograms->map(function ($program) {
            $program->tools = json_decode($program->tools, true) ?? [];
            $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
            $program->benefits = json_decode($program->benefits, true) ?? [];
            $program->available_slots = $program->quota - $program->enrolled_count;
            return $program;
        });

        return view('client.home', compact('popularPrograms', 'instructors'));
    }
}
