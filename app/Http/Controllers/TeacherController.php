<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::with('user')->where('is_active', true)->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:teachers,nip',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
        ]);

        // Create user account for teacher
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
            'role' => 'guru',
            'is_active' => true,
        ]);

        // Create teacher record
        Teacher::create([
            'user_id' => $newUser->id,
            'nip' => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'mata_pelajaran' => $validated['mata_pelajaran'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'gaji_pokok' => $validated['gaji_pokok'],
            'tunjangan' => $validated['tunjangan'] ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $user = Auth::user();

        // Guru hanya bisa melihat data dirinya sendiri
        if ($user->role === 'guru' && $teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|unique:teachers,nip,' . $teacher->id,
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
        ]);

        // Update user
        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update teacher
        $teacher->update([
            'nip' => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'mata_pelajaran' => $validated['mata_pelajaran'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'gaji_pokok' => $validated['gaji_pokok'],
            'tunjangan' => $validated['tunjangan'] ?? 0,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Soft delete - deactivate instead of actual delete
        $teacher->update(['is_active' => false]);
        $teacher->user->update(['is_active' => false]);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil dinonaktifkan.');
    }
}
