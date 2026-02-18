<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class InstructorController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Instruktur & Pengajar');
        SEOTools::setDescription('Kenalan dengan para instruktur dan pengajar berpengalaman di Sukarobot Academy yang siap membimbing Anda.');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'profile');

        $instructors = Instructor::join('users', function($join) {
            $join->on('users.email', '=', \Illuminate\Support\Facades\DB::raw('data_trainers.email COLLATE utf8mb4_unicode_ci'));
        })
            ->where('data_trainers.status_trainer', 'Aktif')
            ->where('users.role', 'instructor')
            ->select(
                'users.email', // Add email for unique filtering
                'users.name as nama',
                'users.avatar',
                'users.job as jabatan',
                'data_trainers.pengalaman',
                'data_trainers.keahlian',
                'data_trainers.bio as deskripsi', // data_trainers.bio mapped to deskripsi
                'data_trainers.foto'
            )
            ->get()
            ->unique('email') // Filter unique instructors by email
            ->values() // Reset keys to ensure JSON array, not object
            ->map(function ($instructor) {
                // Determine photo to use: instructor-specific photo > user avatar > default
                $defaultAvatar = asset('assets/elearning/client/img/default-avatar.jpeg');
                $photo = $defaultAvatar;

                // Check Instructor specific photo
                if ($instructor->foto) {
                     if (filter_var($instructor->foto, FILTER_VALIDATE_URL)) {
                         $photo = $instructor->foto;
                     } elseif (file_exists(public_path($instructor->foto))) {
                         $photo = asset($instructor->foto);
                     } elseif (file_exists(storage_path('app/public/' . $instructor->foto))) {
                         $photo = asset('storage/' . $instructor->foto);
                     }
                } 
                
                // Fallback to User avatar if instructor photo not valid/set
                if ($photo === $defaultAvatar && $instructor->avatar) {
                    if (filter_var($instructor->avatar, FILTER_VALIDATE_URL)) {
                        $photo = $instructor->avatar;
                    } else {
                        $exists = false;
                        if (file_exists(public_path($instructor->avatar))) {
                             $exists = true;
                             $photo = asset($instructor->avatar);
                        } elseif (file_exists(public_path('storage/' . $instructor->avatar))) {
                             $exists = true;
                             $photo = asset('storage/' . $instructor->avatar);
                        } elseif (file_exists(storage_path('app/public/' . $instructor->avatar))) {
                             $exists = true;
                             $photo = asset('storage/' . $instructor->avatar);
                        }
                    }
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
