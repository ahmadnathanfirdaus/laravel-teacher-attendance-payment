<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SelfAttendanceController extends Controller
{
    /**
     * Show camera attendance form
     */
    public function index()
    {
        $user = Auth::user();

        // Only teachers can access this
        if ($user->role !== 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Data guru tidak ditemukan.');
        }

        // Check if already have attendance today
        $todayAttendance = Attendance::where('teacher_id', $teacher->id)
                                   ->whereDate('tanggal', today())
                                   ->first();

        // Get weekly attendances (current week)
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $weeklyAttendances = Attendance::where('teacher_id', $teacher->id)
                                      ->whereBetween('tanggal', [$weekStart, $weekEnd])
                                      ->get();

        // Get monthly attendances (current month)
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $monthlyAttendances = Attendance::where('teacher_id', $teacher->id)
                                       ->whereBetween('tanggal', [$monthStart, $monthEnd])
                                       ->get();

        // Calculate average working hours
        $totalWorkingMinutes = 0;
        $daysWithBothTimes = 0;

        foreach ($monthlyAttendances as $attendance) {
            if ($attendance->jam_masuk && $attendance->jam_keluar) {
                try {
                    // Parse time values (handles both "H:i:s" and "H:i" formats)
                    $jamMasuk = Carbon::parse("2000-01-01 " . $attendance->jam_masuk);
                    $jamKeluar = Carbon::parse("2000-01-01 " . $attendance->jam_keluar);

                    $totalWorkingMinutes += $jamKeluar->diffInMinutes($jamMasuk);
                    $daysWithBothTimes++;
                } catch (\Exception $e) {
                    // Skip this record if there's a parsing error
                    continue;
                }
            }
        }

        $averageWorkingHours = $daysWithBothTimes > 0 ? round($totalWorkingMinutes / $daysWithBothTimes / 60, 1) : 0;

        // Calculate attendance rate for this month
        $totalWorkingDays = Carbon::now()->day; // Days passed in current month
        $attendedDays = $monthlyAttendances->where('status', 'hadir')->count() + $monthlyAttendances->where('status', 'terlambat')->count();
        $attendanceRate = $totalWorkingDays > 0 ? round(($attendedDays / $totalWorkingDays) * 100) : 0;

        // Get recent attendances (last 5)
        $recentAttendances = Attendance::where('teacher_id', $teacher->id)
                                     ->orderBy('tanggal', 'desc')
                                     ->limit(5)
                                     ->get();

        return view('self-attendance.index', compact(
            'teacher',
            'todayAttendance',
            'weeklyAttendances',
            'monthlyAttendances',
            'averageWorkingHours',
            'attendanceRate',
            'recentAttendances'
        ));
    }

    /**
     * Store attendance with photo
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only teachers can access this
        if ($user->role !== 'guru') {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json(['error' => 'Data guru tidak ditemukan.'], 404);
        }

        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'photo' => 'required|string', // Base64 encoded image
        ]);

        $today = today();
        $currentTime = now();

        // Check if already have attendance today
        $attendance = Attendance::where('teacher_id', $teacher->id)
                                ->whereDate('tanggal', $today)
                                ->first();

        // Decode and save photo
        $photoData = $request->photo;
        $photoData = str_replace('data:image/jpeg;base64,', '', $photoData);
        $photoData = str_replace(' ', '+', $photoData);
        $imageData = base64_decode($photoData);

        $fileName = 'attendance_' . $teacher->id . '_' . $today->format('Y_m_d') . '_' . $request->type . '.jpg';
        $path = 'attendance_photos/' . $fileName;

        Storage::disk('public')->put($path, $imageData);

        if ($request->type === 'masuk') {
            // Clock in
            if ($attendance && $attendance->jam_masuk) {
                return response()->json(['error' => 'Anda sudah melakukan absen masuk hari ini.'], 400);
            }

            // Determine status based on time and assigned shifts
            $jamMasukStandar = Carbon::createFromTime(7, 0, 0); // Default: 07:00
            $activeShift = null;

            // Check if teacher has assigned shifts for today
            $dayName = strtolower($today->format('l')); // Get day name in English
            $dayNameIndonesian = [
                'monday' => 'senin',
                'tuesday' => 'selasa',
                'wednesday' => 'rabu',
                'thursday' => 'kamis',
                'friday' => 'jumat',
                'saturday' => 'sabtu',
                'sunday' => 'minggu'
            ][$dayName] ?? 'senin';

            if ($teacher->shifts->count() > 0 && $teacher->working_days && in_array($dayNameIndonesian, $teacher->working_days)) {
                // Find the earliest shift for today
                $activeShift = $teacher->activeShifts()
                    ->orderBy('start_time')
                    ->first();

                if ($activeShift) {
                    $jamMasukStandar = Carbon::createFromFormat('H:i:s', $activeShift->start_time);
                }
            }

            $status = $currentTime->format('H:i') <= $jamMasukStandar->format('H:i') ? 'hadir' : 'terlambat';

            if ($attendance) {
                // Update existing record
                $attendance->update([
                    'jam_masuk' => $currentTime->format('H:i'),
                    'status' => $status,
                    'photo_masuk' => $path,
                    'shift_id' => $activeShift ? $activeShift->id : null,
                ]);
            } else {
                // Create new record
                $attendance = Attendance::create([
                    'teacher_id' => $teacher->id,
                    'tanggal' => $today,
                    'jam_masuk' => $currentTime->format('H:i'),
                    'status' => $status,
                    'photo_masuk' => $path,
                    'shift_id' => $activeShift ? $activeShift->id : null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil dicatat.',
                'status' => $status,
                'time' => $currentTime->format('H:i'),
            ]);

        } else {
            // Clock out
            if (!$attendance || !$attendance->jam_masuk) {
                return response()->json(['error' => 'Anda belum melakukan absen masuk hari ini.'], 400);
            }

            if ($attendance->jam_keluar) {
                return response()->json(['error' => 'Anda sudah melakukan absen keluar hari ini.'], 400);
            }

            $attendance->update([
                'jam_keluar' => $currentTime->format('H:i'),
                'photo_keluar' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absen keluar berhasil dicatat.',
                'time' => $currentTime->format('H:i'),
            ]);
        }
    }

    /**
     * Get current location
     */
}
