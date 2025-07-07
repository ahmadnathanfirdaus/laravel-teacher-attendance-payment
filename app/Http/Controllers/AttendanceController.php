<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            // Guru hanya bisa melihat absensi dirinya sendiri
            $teacher = $user->teacher;
            $attendances = Attendance::where('teacher_id', $teacher->id)
                                   ->when($request->month, function($query, $month) {
                                       return $query->whereMonth('tanggal', $month);
                                   })
                                   ->when($request->year, function($query, $year) {
                                       return $query->whereYear('tanggal', $year);
                                   })
                                   ->orderBy('tanggal', 'desc')
                                   ->paginate(10);
        } else {
            // Admin dan bendahara bisa melihat semua absensi
            $attendances = Attendance::with('teacher')
                                   ->when($request->teacher_id, function($query, $teacherId) {
                                       return $query->where('teacher_id', $teacherId);
                                   })
                                   ->when($request->month, function($query, $month) {
                                       return $query->whereMonth('tanggal', $month);
                                   })
                                   ->when($request->year, function($query, $year) {
                                       return $query->whereYear('tanggal', $year);
                                   })
                                   ->orderBy('tanggal', 'desc')
                                   ->paginate(10);
        }

        $teachers = Teacher::where('is_active', true)->get();

        return view('attendances.index', compact('attendances', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('attendances.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,tidak_hadir,terlambat,izin,sakit',
            'shift_id' => 'nullable|exists:shifts,id',
            'keterangan' => 'nullable|string',
        ]);

        // Validate shift if teacher has assigned shifts
        $teacher = Teacher::find($validated['teacher_id']);
        if ($teacher->shifts->count() > 0 && !$validated['shift_id']) {
            return redirect()->back()->withErrors(['shift_id' => 'Shift harus dipilih untuk guru yang memiliki shift.'])->withInput();
        }

        // Validate if selected shift is assigned to the teacher
        if ($validated['shift_id'] && !$teacher->shifts->contains($validated['shift_id'])) {
            return redirect()->back()->withErrors(['shift_id' => 'Shift yang dipilih tidak sesuai dengan shift guru.'])->withInput();
        }

        Attendance::create($validated);

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $user = Auth::user();

        // Guru hanya bisa melihat absensi dirinya sendiri
        if ($user->role === 'guru' && $attendance->teacher->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teachers = Teacher::where('is_active', true)->get();
        return view('attendances.edit', compact('attendance', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,tidak_hadir,terlambat,izin,sakit',
            'keterangan' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
