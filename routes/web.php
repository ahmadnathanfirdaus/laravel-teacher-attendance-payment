<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SelfAttendanceController;
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
