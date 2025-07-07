<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SelfAttendanceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AllowanceTypeController;
use App\Http\Controllers\TeacherAllowanceController;
use App\Http\Controllers\EducationLevelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teachers routes
    Route::middleware(['role:admin,bendahara'])->group(function () {
        Route::resource('teachers', TeacherController::class)->except(['show']);

        // Additional teacher management routes
        Route::get('/teachers/{teacher}/shifts', [TeacherController::class, 'shifts'])->name('teachers.shifts');
        Route::put('/teachers/{teacher}/shifts', [TeacherController::class, 'updateShifts'])->name('teachers.shifts.update');
        Route::get('/teachers/{teacher}/allowances', [TeacherController::class, 'allowances'])->name('teachers.allowances');
    });

    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        // Shifts management
        Route::resource('shifts', ShiftController::class);
        Route::patch('/shifts/{shift}/toggle-status', [ShiftController::class, 'toggleStatus'])->name('shifts.toggle-status');

        // Positions management
        Route::resource('positions', PositionController::class);
        Route::patch('/positions/{position}/toggle-status', [PositionController::class, 'toggleStatus'])->name('positions.toggle-status');

        // Education Levels management
        Route::resource('education-levels', EducationLevelController::class);
        Route::patch('/education-levels/{educationLevel}/toggle-status', [EducationLevelController::class, 'toggleStatus'])->name('education-levels.toggle-status');

        // Allowance Types management
        Route::resource('allowance-types', AllowanceTypeController::class);
        Route::patch('/allowance-types/{allowanceType}/toggle-status', [AllowanceTypeController::class, 'toggleStatus'])->name('allowance-types.toggle-status');

        // Teacher Allowances management
        Route::resource('teacher-allowances', TeacherAllowanceController::class);
        Route::patch('/teacher-allowances/{teacherAllowance}/toggle-status', [TeacherAllowanceController::class, 'toggleStatus'])->name('teacher-allowances.toggle-status');
    });

    // Teachers show route - accessible by all authenticated users with restrictions inside controller
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

    // Attendance routes
    Route::resource('attendances', AttendanceController::class);

    // Salary routes
    Route::resource('salaries', SalaryController::class);

    // Self Attendance routes (for teachers only)
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/self-attendance', [SelfAttendanceController::class, 'index'])->name('self-attendance.index');
        Route::post('/self-attendance', [SelfAttendanceController::class, 'store'])->name('self-attendance.store');
        Route::post('/self-attendance/location', [SelfAttendanceController::class, 'getLocation'])->name('self-attendance.location');
    });
});
