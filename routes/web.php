<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;

// Auth routes (must be on web middleware for session-based Sanctum SPA auth)
Route::middleware('web')->group(function () {
    Route::post('/api/login', [AuthController::class, 'login']);
    Route::post('/api/logout', [AuthController::class, 'logout']);
});

// Protected API routes using Sanctum + session/CSRF via web middleware
Route::middleware(['web', 'auth:sanctum'])->prefix('api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    // Setup
    Route::get('/setup/status', [SetupController::class, 'status']);
    Route::post('/setup/complete', [SetupController::class, 'complete']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Employees
    Route::apiResource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/shifts', [EmployeeController::class, 'shifts']);
    Route::post('/employees/{employee}/shifts', [EmployeeController::class, 'assignShifts']);

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Designations
    Route::get('/designations', [DesignationController::class, 'index']);

    // Shifts
    Route::apiResource('shifts', ShiftController::class);

    // Employment Statuses
    Route::get('/employment-statuses', [EmploymentStatusController::class, 'index']);

    // Attendance
    Route::apiResource('attendance', AttendanceController::class);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);

    // Payrolls
    Route::apiResource('payrolls', PayrollController::class);
    Route::post('/payrolls/generate', [PayrollController::class, 'generate']);
    Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'download']);
});

// Redirect root to Vue frontend login page
Route::get('/', function () {
    return redirect('http://localhost:5174/login');
});

// Catch all other web routes and redirect to frontend
Route::fallback(function () {
    return redirect('http://localhost:5174/login');
});

