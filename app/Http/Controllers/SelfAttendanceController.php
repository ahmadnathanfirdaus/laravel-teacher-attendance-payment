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

        return view('self-attendance.index', compact('teacher', 'todayAttendance'));
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
            'location' => 'nullable|string',
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

            // Determine status based on time
            $jamMasukStandar = Carbon::createFromTime(7, 0, 0); // 07:00
            $status = $currentTime->format('H:i') <= $jamMasukStandar->format('H:i') ? 'hadir' : 'terlambat';

            if ($attendance) {
                // Update existing record
                $attendance->update([
                    'jam_masuk' => $currentTime->format('H:i'),
                    'status' => $status,
                    'photo_masuk' => $path,
                    'location' => $request->location,
                    'keterangan' => $request->location ? 'Lokasi: ' . $request->location : null,
                ]);
            } else {
                // Create new record
                $attendance = Attendance::create([
                    'teacher_id' => $teacher->id,
                    'tanggal' => $today,
                    'jam_masuk' => $currentTime->format('H:i'),
                    'status' => $status,
                    'photo_masuk' => $path,
                    'location' => $request->location,
                    'keterangan' => $request->location ? 'Lokasi: ' . $request->location : null,
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
    public function getLocation(Request $request)
    {
        // This would typically integrate with a mapping service
        // For now, we'll just return the coordinates
        return response()->json([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => 'Koordinat: ' . $request->latitude . ', ' . $request->longitude
        ]);
    }
}
