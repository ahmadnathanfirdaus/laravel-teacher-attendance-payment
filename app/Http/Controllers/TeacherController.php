<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Position;
use App\Models\Shift;
use App\Models\EducationLevel;
use App\Models\AllowanceType;
use App\Models\TeacherAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $teachers = Teacher::with(['user', 'position', 'shifts', 'educationLevel'])
            ->where('is_active', true)
            ->paginate(10);
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

        $positions = Position::active()->get();
        $shifts = Shift::active()->get();
        $educationLevels = EducationLevel::active()->get();
        $allowanceTypes = AllowanceType::active()->get();

        return view('teachers.create', compact('positions', 'shifts', 'educationLevels', 'allowanceTypes'));
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
            'position_id' => 'nullable|exists:positions,id',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'shift_id' => 'nullable|exists:shifts,id',
            'allowance_types' => 'nullable|array',
            'allowance_types.*' => 'exists:allowance_types,id',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teacher-photos', 'public');
        }

        // Create user account for teacher
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
            'role' => 'guru',
            'is_active' => true,
        ]);

        // Create teacher record
        $teacher = Teacher::create([
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
            'position_id' => $validated['position_id'] ?? null,
            'education_level_id' => $validated['education_level_id'] ?? null,
            'photo_path' => $photoPath,
            'working_days' => $validated['working_days'] ?? null,
            'is_active' => true,
        ]);

        // Attach shift if provided
        if (!empty($validated['shift_id'])) {
            $teacher->shifts()->attach($validated['shift_id'], [
                'days' => implode(',', $validated['working_days'] ?? []),
                'effective_date' => $validated['tanggal_masuk'],
                'is_active' => true,
            ]);
        }

        // Attach allowance types if provided
        if (isset($validated['allowance_types']) && !empty($validated['allowance_types'])) {
            foreach ($validated['allowance_types'] as $allowanceTypeId) {
                $allowanceType = AllowanceType::find($allowanceTypeId);
                if ($allowanceType) {
                    $teacher->teacherAllowances()->create([
                        'allowance_type_id' => $allowanceTypeId,
                        'amount' => $allowanceType->default_amount,
                        'effective_date' => now(),
                        'is_active' => true,
                    ]);
                }
            }
        }

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

        $teacher->load(['position', 'shifts', 'teacherAllowances.allowanceType', 'educationLevel']);

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

        $positions = Position::active()->get();
        $shifts = Shift::active()->get();
        $educationLevels = EducationLevel::active()->get();
        $allowanceTypes = AllowanceType::active()->get();

        // Load teacher allowances
        $teacher->load(['teacherAllowances']);

        return view('teachers.edit', compact('teacher', 'positions', 'shifts', 'educationLevels', 'allowanceTypes'));
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
            'position_id' => 'nullable|exists:positions,id',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'shift_id' => 'nullable|exists:shifts,id',
            'allowance_types' => 'nullable|array',
            'allowance_types.*' => 'exists:allowance_types,id',
        ]);

        // Handle photo upload
        $photoPath = $teacher->photo_path;
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($teacher->photo_path) {
                Storage::disk('public')->delete($teacher->photo_path);
            }
            $photoPath = $request->file('photo')->store('teacher-photos', 'public');
        }

        // Update user
        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update teacher record
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
            'position_id' => $validated['position_id'] ?? null,
            'education_level_id' => $validated['education_level_id'] ?? null,
            'photo_path' => $photoPath,
            'working_days' => $validated['working_days'] ?? null,
        ]);

        // Update shift
        $teacher->shifts()->detach(); // Remove all existing shifts
        if (!empty($validated['shift_id'])) {
            $teacher->shifts()->attach($validated['shift_id'], [
                'days' => implode(',', $validated['working_days'] ?? []),
                'effective_date' => now(),
                'is_active' => true,
            ]);
        }

        // Update allowance types
        if (isset($validated['allowance_types'])) {
            // Remove existing allowances
            $teacher->teacherAllowances()->delete();

            // Add new allowances
            foreach ($validated['allowance_types'] as $allowanceTypeId) {
                $allowanceType = AllowanceType::find($allowanceTypeId);
                if ($allowanceType) {
                    $teacher->teacherAllowances()->create([
                        'allowance_type_id' => $allowanceTypeId,
                        'amount' => $allowanceType->default_amount,
                        'effective_date' => now(),
                        'is_active' => true,
                    ]);
                }
            }
        } else {
            // If no allowance types selected, remove all existing allowances
            $teacher->teacherAllowances()->delete();
        }

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
