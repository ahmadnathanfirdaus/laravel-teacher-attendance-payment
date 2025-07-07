<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\User;
use App\Models\EducationLevel;
use App\Models\AllowanceType;
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
        $totalEducationLevels = EducationLevel::where('is_active', true)->count();
        $totalAllowanceTypes = AllowanceType::where('is_active', true)->count();

        $recentAttendances = Attendance::with('teacher')
                                     ->whereDate('tanggal', today())
                                     ->latest()
                                     ->take(5)
                                     ->get();

        return view('dashboard.admin', compact(
            'totalTeachers',
            'totalAttendanceToday',
            'totalSalariesThisMonth',
            'totalEducationLevels',
            'totalAllowanceTypes',
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

        // Get teacher's active allowances
        $activeAllowances = $teacher->teacherAllowances()
                                   ->with('allowanceType')
                                   ->where('is_active', true)
                                   ->get();

        // Calculate total allowances
        $totalAllowances = $activeAllowances->sum('amount');

        // Add position allowance if exists
        $positionAllowance = 0;
        if ($teacher->position && $teacher->position->base_allowance > 0) {
            $positionAllowance = $teacher->position->base_allowance;
            $totalAllowances += $positionAllowance;
        }

        return view('dashboard.teacher', compact(
            'teacher',
            'myAttendanceThisMonth',
            'mySalaryThisMonth',
            'recentAttendances',
            'activeAllowances',
            'totalAllowances',
            'positionAllowance'
        ));
    }
}
