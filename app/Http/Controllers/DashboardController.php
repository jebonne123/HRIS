<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $today = Carbon::today();

        // Top cards
        $totalEmployees = Employee::count();
        $totalDepartments = class_exists(Department::class) ? Department::count() : 0;
        $totalLeaveRequests = class_exists('App\\Models\\LeaveRequest') ? \App\Models\LeaveRequest::count() : 0;
        $onLeaveToday = class_exists('App\\Models\\LeaveRequest')
            ? \App\Models\LeaveRequest::whereDate('date', $today)->count()
            : 0;

        // Attendance summary for today
        $presentToday = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $lateToday = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $earlyToday = Attendance::whereDate('date', $today)->where('status', 'early')->count();
        $regularToday = max($presentToday - $lateToday - $earlyToday, 0);

        // Employee stats by status/department
        // Group employees by employment status when the column exists.
        // Fallback: return a single "Permanent" bucket with total employees so the chart has data.
        if (Schema::hasTable('employment_statuses') && Schema::hasColumn('employees', 'employment_status_id')) {
            // Left join to include zero-count statuses
            $byEmploymentStatus = \DB::table('employment_statuses as es')
                ->leftJoin('employees as e', 'e.employment_status_id', '=', 'es.id')
                ->selectRaw("es.name as label, COUNT(e.id) as count")
                ->groupBy('es.name')
                ->orderBy('es.name')
                ->get();
        } else if (Schema::hasColumn('employees', 'employment_status')) {
            $byEmploymentStatus = \DB::table('employees')
                ->selectRaw("COALESCE(employment_status, 'Unknown') as label, COUNT(*) as count")
                ->groupBy('employment_status')
                ->orderBy('label')
                ->get();
        } else {
            $byEmploymentStatus = collect([
                (object) ['label' => 'Permanent', 'count' => $totalEmployees],
            ]);
        }

        // Include all departments even if they have zero employees
        if (Schema::hasTable('departments')) {
            $byDepartment = \DB::table('departments as d')
                ->leftJoin('employees as e', 'e.department_id', '=', 'd.id')
                ->selectRaw("d.name as label, COUNT(e.id) as count")
                ->groupBy('d.name')
                ->orderBy('d.name')
                ->get();
        } else {
            // Fallback when departments table doesn't exist
            $byDepartment = collect([
                (object) ['label' => 'Unassigned', 'count' => Employee::whereNull('department_id')->count()],
            ]);
        }

        return response()->json([
            'cards' => [
                'total_employees' => $totalEmployees,
                'total_departments' => $totalDepartments,
                'total_leave_requests' => $totalLeaveRequests,
                'on_leave_today' => $onLeaveToday,
            ],
            'employee_stats' => [
                'by_status' => $byEmploymentStatus,
                'by_department' => $byDepartment,
            ],
            'attendance_today' => [
                'total' => ($presentToday + $lateToday + $earlyToday),
                'early' => $earlyToday,
                'late' => $lateToday,
                'regular' => $regularToday,
            ],
        ]);
    }

    private function getOnShiftCount()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        
        return \DB::table('shifts')
            ->join('employee_shifts', 'shifts.id', '=', 'employee_shifts.shift_id')
            ->where('employee_shifts.effective_from', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->where('employee_shifts.effective_until', '>=', $now)
                      ->orWhereNull('employee_shifts.effective_until');
            })
            ->where(function ($query) use ($currentTime) {
                $query->where(function ($q) use ($currentTime) {
                    $q->where('shifts.start_time_1', '<=', $currentTime)
                      ->where('shifts.end_time_1', '>=', $currentTime);
                })->orWhere(function ($q) use ($currentTime) {
                    $q->where('shifts.start_time_2', '<=', $currentTime)
                      ->where('shifts.end_time_2', '>=', $currentTime);
                });
            })
            ->count();
    }

    private function getPayrollSummary()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        
        return [
            'current_month' => Payroll::where('period_start', '>=', $currentMonth)->count(),
            'pending' => Payroll::where('status', 'draft')->count(),
            'approved' => Payroll::where('status', 'approved')->count(),
            'paid' => Payroll::where('status', 'paid')->count(),
        ];
    }

    private function getPendingApprovals()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isHR()) {
            return [
                'leave_requests' => 0, // Placeholder for future implementation
                'overtime_requests' => 0, // Placeholder for future implementation
                'expense_claims' => 0, // Placeholder for future implementation
            ];
        }
        
        return [
            'leave_requests' => 0,
            'overtime_requests' => 0,
            'expense_claims' => 0,
        ];
    }
}



