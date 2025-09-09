<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;

// Public routes
// (login moved to web routes to enable session usage)

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // (logout moved to web routes to ensure session is available)
    Route::get('/user', [AuthController::class, 'user']);
    
    // Setup routes
    Route::get('/setup/status', [SetupController::class, 'status']);
    Route::post('/setup/complete', [SetupController::class, 'complete']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Employee management
    Route::apiResource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/shifts', [EmployeeController::class, 'shifts']);
    Route::post('/employees/{employee}/shifts', [EmployeeController::class, 'assignShifts']);
    
    // Department management
    Route::apiResource('departments', DepartmentController::class);
    
    // Shift management
    Route::apiResource('shifts', ShiftController::class);
    
    // Attendance management
    Route::apiResource('attendance', AttendanceController::class);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    
    // Payroll management
    Route::apiResource('payrolls', PayrollController::class);
    Route::post('/payrolls/generate', [PayrollController::class, 'generate']);
    Route::get('/payrolls/{payroll}/download', [PayrollController::class, 'download']);
});
