<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Halaman untuk konfirmasi akun instruktur yang mendaftar
     */
    public function index()
    {
        // Get instructors from users table with role='instructor' or role='trainer'
        $instructors = DB::table('users')
            ->whereIn('role', ['instructor', 'trainer'])
            ->select('id', 'name', 'email', 'phone', 'avatar', 'is_active', 'created_at', 'last_login_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
                'expertise' => $trainer->lulusan ?? '-',
                'status' => $user->is_active ? 'Aktif' : 'Tidak Aktif',
                'created_at' => $user->created_at ? date('Y-m-d', strtotime($user->created_at)) : '-',
                'has_trainer_data' => $trainer ? true : false
            ];
        });

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
     * Store a newly created instructor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|string|in:Approved,Pending,Rejected',
            'expertise' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $isActive = ($validated['status'] ?? 'Approved') === 'Approved';

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
            'is_active' => $isActive ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Upload photo jika ada (opsional)
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $uploadPath = public_path('uploads/instructors');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $photo->move($uploadPath, $photoName);
            $data['avatar'] = 'uploads/instructors/' . $photoName;
        }

        // Insert ke tabel users
        $userId = DB::table('users')->insertGetId($data);

        // Insert ke tabel data_trainers jika ada data tambahan
        if (!empty($validated['expertise']) || !empty($validated['job']) || !empty($validated['experience'])) {
            DB::table('data_trainers')->insert([
                'nama' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['phone'] ?? null,
                'lulusan' => $validated['expertise'] ?? null,
                'status_trainer' => $isActive ? 'Aktif' : 'Tidak Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Instruktur berhasil dihapus']);
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

