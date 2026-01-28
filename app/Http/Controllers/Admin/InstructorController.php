<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Halaman untuk konfirmasi akun instruktur yang mendaftar
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['name', 'email', 'phone', 'is_active', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Get instructors from users table with role='instructor'
        $query = DB::table('users')
            ->where('role', 'instructor')
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('name', 'like', '%' . $s . '%')
                        ->orWhere('email', 'like', '%' . $s . '%')
                        ->orWhere('phone', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->input('status');
                if ($status === 'active') {
                     $query->where('is_active', 1);
                } elseif ($status === 'inactive') {
                     $query->where('is_active', 0);
                }
            })
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at', 'last_login_at');

        $instructors = $query->orderBy($sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $instructors->getCollection()->transform(function($user) {
            // Check if instructor has data in data_trainers table
            $trainer = DB::table('data_trainers')
                ->where('email', $user->email)
                ->first();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email ?? '-',
                'phone' => $user->phone ?? '-',
                'avatar' => $user->avatar,
                'expertise' => $trainer->keahlian ?? '-',
                'status' => $user->is_active ? 'Aktif' : 'Tidak Aktif',
                'is_active' => $user->is_active,
                'created_at' => $user->created_at ? date('Y-m-d', strtotime($user->created_at)) : '-',
                'has_trainer_data' => $trainer ? true : false
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($instructors);
        }

        return view('admin.instructors.index', compact('instructors'));
    }



    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('admin.instructors.create');
    }

    /**
     * Display the specified instructor details.
     */
    public function show($id)
    {
        // Get instructor from users table
        $instructor = DB::table('users')
            ->where('id', $id)
            ->where('role', 'instructor')
            ->first();

        if (!$instructor) {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'Instruktur tidak ditemukan.');
        }

        // Get instructor application data for documents (CV, KTP, NPWP)
        $applicationData = DB::table('instructor_applications')
            ->where('user_id', $id)
            ->first();

        // Merge application data to instructor object
        if ($applicationData) {
            $instructor->cv_path = $applicationData->cv_path ?? null;
            $instructor->ktp_path = $applicationData->ktp_path ?? null;
            $instructor->npwp_path = $applicationData->npwp_path ?? null;
            $instructor->skills = $applicationData->skills ?? null;
            $instructor->application_id = $applicationData->id ?? null;
        }

        return view('admin.instructors.detail', compact('instructor'));
    }

    /**
     * Download private document (CV, KTP, or NPWP) for an instructor - Admin only
     */
    public function downloadDocument($id, $type)
    {
        // Get application data for the instructor
        $applicationData = DB::table('instructor_applications')
            ->where('user_id', $id)
            ->first();
        
        if (!$applicationData) {
            abort(404, 'Data aplikasi instruktur tidak ditemukan');
        }

        $pathField = $type . '_path';
        $validTypes = ['cv', 'ktp', 'npwp'];
        
        if (!in_array($type, $validTypes) || empty($applicationData->$pathField)) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        $path = $applicationData->$pathField;
        
        // Check if file exists in storage
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'nullable|string|in:Aktif,Non-Aktif',
                'expertise' => 'nullable|string|max:255',
                'job' => 'nullable|string|max:255',
                'experience' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

            $data = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'instructor',
                'is_active' => $isActive ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Upload photo jika ada (cropped atau original)
            $croppedPhoto = $request->input('cropped_photo');
            
            if ($croppedPhoto && str_starts_with($croppedPhoto, 'data:image')) {
                // Decode base64 cropped image
                $imageData = explode(',', $croppedPhoto);
                $decodedImage = base64_decode($imageData[1]);
                
                // Generate unique filename
                $photoName = time() . '_cropped_new.png';
                
                // Save cropped image to storage
                Storage::disk('public')->put('users/' . $photoName, $decodedImage);
                $data['avatar'] = 'users/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                // Fallback: Upload original file if no cropped version
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $data['avatar'] = $photo->storeAs('users', $photoName, 'public');
            }

            // Add job to users data if provided
            if (!empty($validated['job'])) {
                $data['job'] = $validated['job'];
            }

            // Insert ke tabel users
            $userId = DB::table('users')->insertGetId($data);

            // Insert ke tabel data_trainers jika ada data tambahan (hanya kolom yang ada di tabel)
            if (!empty($validated['expertise'])) {
                DB::table('data_trainers')->insert([
                    'nama' => $validated['name'],
                    'email' => $validated['email'],
                    'telephone' => $validated['phone'] ?? null,
                    'keahlian' => $validated['expertise'] ?? null,
                    'status_trainer' => $isActive ? 'Aktif' : 'Tidak Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan instruktur: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.instructors.index')->with('error', 'Instruktur tidak ditemukan');
        }

        // Get trainer data if exists
        $trainer = DB::table('data_trainers')->where('email', $user->email)->first();

        $instructorData = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username ?? '',
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'status' => $user->is_active ? 'Aktif' : 'Non-Aktif',
            'expertise' => $trainer->keahlian ?? '',
            'job' => $user->job ?? '',
            'experience' => $trainer->pengalaman ?? '', // Ambil dari tabel data_trainers kolom pengalaman
            'bio' => $trainer->bio ?? '',
            'photo' => $user->avatar ?? null,
        ];

        return view('admin.instructors.edit', ['instructor' => (object)$instructorData]);
    }

    /**
     * Update the specified instructor in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
            
            if (!$user) {
                return redirect()->route('admin.instructors.index')->with('error', 'Instruktur tidak ditemukan');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'nullable|string|in:Aktif,Non-Aktif',
                'expertise' => 'nullable|string|max:255', // data_trainers.keahlian
                'job' => 'nullable|string|max:255', // users.job
                'experience' => 'nullable|string|max:255', // data_trainers.pengalaman
                'bio' => 'nullable|string', // data_trainers.bio
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $isActive = ($validated['status'] ?? 'Aktif') === 'Aktif';

            $data = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'is_active' => $isActive ? 1 : 0,
                'updated_at' => now(),
            ];

            // Update password if provided
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            // Upload photo jika ada (cropped atau original)
            $croppedPhoto = $request->input('cropped_photo');
            
            if ($croppedPhoto && str_starts_with($croppedPhoto, 'data:image')) {
                // Delete old photo if exists (menghindari duplikasi storage)
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    try {
                        unlink(public_path($user->avatar));
                    } catch (\Exception $e) {
                        // Ignore error if file not found
                    }
                }
                
                // Decode base64 cropped image
                $imageData = explode(',', $croppedPhoto);
                $decodedImage = base64_decode($imageData[1]);
                
                // Generate unique filename
                $photoName = time() . '_cropped_' . $id . '.png';
                
                // Save cropped image to storage
                Storage::disk('public')->put('users/' . $photoName, $decodedImage);
                $data['avatar'] = 'users/' . $photoName;
            } elseif ($request->hasFile('photo')) {
                // Fallback: Upload original file if no cropped version
                // Delete old photo if exists
                if ($user->avatar) {
                    if (Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    } elseif (file_exists(public_path($user->avatar))) {
                        try {
                            unlink(public_path($user->avatar));
                        } catch (\Exception $e) {
                            // Ignore error if file not found
                        }
                    }
                }
                
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $data['avatar'] = $photo->storeAs('users', $photoName, 'public');
            }

            // Add job to users data if provided
            if (!empty($validated['job'])) {
                $data['job'] = $validated['job'];
            }

            // Update users table
            DB::table('users')->where('id', $id)->update($data);

            // Update or insert data_trainers
            $trainer = DB::table('data_trainers')->where('email', $user->email)->first();
            $trainerData = [
                'nama' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['phone'] ?? null,
                'keahlian' => $validated['expertise'] ?? null,
                'pengalaman' => $validated['experience'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'status_trainer' => $isActive ? 'Aktif' : 'Tidak Aktif',
                'updated_at' => now(),
            ];

            if ($trainer) {
                // Jika email berubah update juga email di data_trainers (perlu hati-hati jika trigger logic lain, tapi ini standar)
                // Namun karena where('email', $user->email) memakai email LAMA, kita update record tersebut.
                DB::table('data_trainers')->where('email', $user->email)->update($trainerData);
            } else {
                $trainerData['created_at'] = now();
                DB::table('data_trainers')->insert($trainerData);
            }

            return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate instruktur: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();

            if ($user && $user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Hapus data di tabel data_trainers jika email cocok
            if ($user) {
                DB::table('data_trainers')->where('email', $user->email)->delete();
            }

            DB::table('users')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Instruktur berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus instruktur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Approve instructor registration
     */
    public function approve($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.instructors.index')->with('error', 'Instruktur tidak ditemukan');
        }

        // Update status menjadi aktif
        DB::table('users')
            ->where('id', $id)
            ->update([
                'is_active' => 1,
                'updated_at' => now(),
            ]);

        // Update status di data_trainers jika ada
        $trainer = DB::table('data_trainers')->where('email', $user->email)->first();
        if ($trainer) {
            DB::table('data_trainers')
                ->where('email', $user->email)
                ->update([
                    'status_trainer' => 'Aktif',
                    'updated_at' => now(),
                ]);
        }
        
        return redirect()->route('admin.instructors.index')->with('success', 'Akun instruktur berhasil disetujui');
    }

    /**
     * Reject instructor registration
     */
    public function reject($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.instructors.index')->with('error', 'Instruktur tidak ditemukan');
        }

        // Update status menjadi tidak aktif
        DB::table('users')
            ->where('id', $id)
            ->update([
                'is_active' => 0,
                'updated_at' => now(),
            ]);

        // Update status di data_trainers jika ada
        $trainer = DB::table('data_trainers')->where('email', $user->email)->first();
        if ($trainer) {
            DB::table('data_trainers')
                ->where('email', $user->email)
                ->update([
                    'status_trainer' => 'Tidak Aktif',
                    'updated_at' => now(),
                ]);
        }
        
        return redirect()->route('admin.instructors.index')->with('success', 'Pendaftaran instruktur ditolak');
    }
}