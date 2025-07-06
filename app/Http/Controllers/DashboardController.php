<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->role === 'bendahara') {
            return $this->adminDashboard();
        } else if ($user->role === 'guru') {
            return $this->teacherDashboard();
        }

        abort(403, 'Unauthorized access.');
    }

    private function adminDashboard()
    {
        $totalTeachers = Teacher::where('is_active', true)->count();
        $totalAttendanceToday = Attendance::whereDate('tanggal', today())->count();
        $totalSalariesThisMonth = Salary::where('bulan', now()->format('F'))
                                       ->where('tahun', now()->year)
                                       ->count();

        $recentAttendances = Attendance::with('teacher')
                                     ->whereDate('tanggal', today())
                                     ->latest()
                                     ->take(5)
                                     ->get();

        return view('dashboard.admin', compact(
            'totalTeachers',
            'totalAttendanceToday',
            'totalSalariesThisMonth',
            'recentAttendances'
        ));
    }

    private function teacherDashboard()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('login')->with('error', 'Data guru tidak ditemukan.');
        }

        $myAttendanceThisMonth = Attendance::where('teacher_id', $teacher->id)
                                         ->whereMonth('tanggal', now()->month)
                                         ->whereYear('tanggal', now()->year)
                                         ->count();

        $mySalaryThisMonth = Salary::where('teacher_id', $teacher->id)
                                  ->where('bulan', now()->format('F'))
                                  ->where('tahun', now()->year)
                                  ->first();

        $recentAttendances = Attendance::where('teacher_id', $teacher->id)
                                     ->latest()
                                     ->take(5)
                                     ->get();

        return view('dashboard.teacher', compact(
            'teacher',
            'myAttendanceThisMonth',
            'mySalaryThisMonth',
            'recentAttendances'
        ));
    }
}
