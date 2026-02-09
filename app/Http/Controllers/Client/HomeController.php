<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

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
            // ->where('data_programs.start_date', '>', now()) // Removed to show all programs
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
                        if (file_exists(public_path($instructor->avatar))) {
                             $photo = asset($instructor->avatar);
                        } elseif (file_exists(public_path('storage/' . $instructor->avatar))) {
                             $photo = asset('storage/' . $instructor->avatar);
                        } elseif (file_exists(storage_path('app/public/' . $instructor->avatar))) {
                             $photo = asset('storage/' . $instructor->avatar);
                        }
                    }
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

    /**
     * Handle contact form submission
     */
    public function sendContact(Request $request)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Silahkan login terlebih dahulu untuk mengirim pesan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255', // Email taken from auth
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => auth()->user()->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ];

        try {
            // Send email to specific address for testing
            Mail::to('sukarobotacademy@gmail.com')->send(new ContactMessage($data));

            return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
        } catch (\Exception $e) {
            return back()->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti. ' . $e->getMessage());
        }
    }
}
