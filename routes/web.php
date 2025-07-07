<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SelfAttendanceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AllowanceTypeController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\LeaveRequestController;
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


    });

    // Teachers show route - accessible by all authenticated users with restrictions inside controller
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

    // Attendance routes
    Route::resource('attendances', AttendanceController::class);

    // Salary routes
    Route::resource('salaries', SalaryController::class);
    Route::get('/salaries-bulk/create', [SalaryController::class, 'bulkCreate'])->name('salaries.bulk-create');
    Route::post('/salaries-bulk', [SalaryController::class, 'bulkStore'])->name('salaries.bulk-store');

    // Leave Request routes
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::patch('/leave-requests/{leave_request}/status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');

    // Self Attendance routes (for teachers only)
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/self-attendance', [SelfAttendanceController::class, 'index'])->name('self-attendance.index');
        Route::post('/self-attendance', [SelfAttendanceController::class, 'store'])->name('self-attendance.store');
    });
});
