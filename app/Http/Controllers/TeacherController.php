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

        $teachers = Teacher::with(['user', 'shifts', 'educationLevel'])
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
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'salary_type' => 'required|in:per_hari,per_jam,per_bulan',
            'positions' => 'required|array|min:1',
            'positions.*' => 'exists:positions,id',
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
            'password_changed' => false, // User needs to change password on first login
        ]);

        // Create teacher record
        $teacher = Teacher::create([
            'user_id' => $newUser->id,
            'nip' => $validated['nip'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'mata_pelajaran' => $validated['mata_pelajaran'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'nominal' => $validated['nominal'],
            'salary_type' => $validated['salary_type'],
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

        // Attach multiple positions if provided
        if (isset($validated['positions']) && !empty($validated['positions'])) {
            foreach ($validated['positions'] as $positionId) {
                $teacher->positions()->attach($positionId, [
                    'is_active' => true,
                    'notes' => 'Initial assignment',
                ]);
            }
        }

        // Attach allowance types with calculation types if provided
        if (isset($validated['allowance_types']) && !empty($validated['allowance_types'])) {
            foreach ($validated['allowance_types'] as $allowanceTypeId) {
                $allowanceType = AllowanceType::find($allowanceTypeId);
                if ($allowanceType) {
                    // Get custom calculation type and amount from request
                    $calculationType = $request->input("allowance_calculation_{$allowanceTypeId}", $allowanceType->calculation_type ?? 'fixed');
                    $customAmount = $request->input("allowance_amount_{$allowanceTypeId}", $allowanceType->default_amount);

                    $teacher->teacherAllowances()->create([
                        'allowance_type_id' => $allowanceTypeId,
                        'amount' => $customAmount,
                        'calculation_type' => $calculationType,
                        'effective_date' => $validated['tanggal_masuk'],
                        'notes' => "Initial assignment - {$calculationType}",
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan. Password default adalah "password123", guru akan diminta mengubah password saat login pertama.');
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

        $teacher->load(['shifts', 'teacherAllowances.allowanceType', 'educationLevel']);

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
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'salary_type' => 'required|in:per_hari,per_jam,per_bulan',
            'nominal' => 'required|numeric|min:0',
            'positions' => 'required|array|min:1',
            'positions.*' => 'exists:positions,id',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'shift_id' => 'nullable|exists:shifts,id',
            'allowance_types' => 'nullable|array',
            'allowance_types.*' => 'exists:allowance_types,id',
            'allowance_calculation_types' => 'nullable|array',
            'allowance_calculation_types.*' => 'in:fixed,per_hari,per_bulan',
            'allowance_amounts' => 'nullable|array',
            'allowance_amounts.*' => 'nullable|numeric|min:0',
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
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'mata_pelajaran' => $validated['mata_pelajaran'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'salary_type' => $validated['salary_type'],
            'nominal' => $validated['nominal'],
            'education_level_id' => $validated['education_level_id'] ?? null,
            'photo_path' => $photoPath,
            'working_days' => $validated['working_days'] ?? null,
        ]);

        // Update positions
        $teacher->positions()->sync($validated['positions']);

        // Update shift
        $teacher->shifts()->detach(); // Remove all existing shifts
        if (!empty($validated['shift_id'])) {
            $teacher->shifts()->attach($validated['shift_id'], [
                'days' => implode(',', $validated['working_days'] ?? []),
                'effective_date' => now(),
                'is_active' => true,
            ]);
        }

        // Update allowance types with enhanced calculation and amounts
        if (isset($validated['allowance_types'])) {
            // Remove existing allowances
            $teacher->teacherAllowances()->delete();

            // Add new allowances with enhanced data
            foreach ($validated['allowance_types'] as $allowanceTypeId) {
                $allowanceType = AllowanceType::find($allowanceTypeId);
                if ($allowanceType) {
                    $calculationType = $validated['allowance_calculation_types'][$allowanceTypeId] ?? $allowanceType->calculation_type ?? 'fixed';
                    $customAmount = !empty($validated['allowance_amounts'][$allowanceTypeId])
                        ? $validated['allowance_amounts'][$allowanceTypeId]
                        : $allowanceType->default_amount;

                    $teacher->teacherAllowances()->create([
                        'allowance_type_id' => $allowanceTypeId,
                        'amount' => $customAmount,
                        'calculation_type' => $calculationType,
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
