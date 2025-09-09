<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee', 'shift']);

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json($attendances);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'fp_id' => 'nullable|string',
            'card_id' => 'nullable|string',
            'pwd' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        
        // Verify employee identity
        if ($request->fp_id && $employee->fp_id !== $request->fp_id) {
            return response()->json(['message' => 'Invalid fingerprint ID'], 422);
        }
        
        if ($request->card_id && $employee->card_id !== $request->card_id) {
            return response()->json(['message' => 'Invalid card ID'], 422);
        }
        
        if ($request->pwd && $employee->pwd !== $request->pwd) {
            return response()->json(['message' => 'Invalid password'], 422);
        }

        $today = Carbon::today();
        $currentShift = $employee->getCurrentShift();

        if (!$currentShift) {
            return response()->json(['message' => 'No active shift found for employee'], 422);
        }

        // Check if already checked in today
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return response()->json(['message' => 'Employee already checked in today'], 422);
        }

        if ($existingAttendance) {
            $existingAttendance->update([
                'check_in' => now(),
                'created_by' => Auth::id(),
            ]);
            $attendance = $existingAttendance;
        } else {
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'shift_id' => $currentShift->id,
                'date' => $today,
                'check_in' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        // Update status based on shift rules
        $attendance->updateStatus();

        return response()->json([
            'message' => 'Check-in successful',
            'attendance' => $attendance->load(['employee', 'shift'])
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = Carbon::today();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json(['message' => 'No check-in record found for today'], 422);
        }

        if ($attendance->check_out) {
            return response()->json(['message' => 'Employee already checked out today'], 422);
        }

        $attendance->update([
            'check_out' => now(),
            'work_minutes' => $attendance->calculateWorkMinutes(),
        ]);

        // Update status
        $attendance->updateStatus();

        return response()->json([
            'message' => 'Check-out successful',
            'attendance' => $attendance->load(['employee', 'shift'])
        ]);
    }

    public function store(AttendanceRequest $request)
    {
        $attendance = Attendance::create($request->validated());

        return response()->json([
            'message' => 'Attendance created successfully',
            'attendance' => $attendance->load(['employee', 'shift'])
        ], 201);
    }

    public function show(Attendance $attendance)
    {
        return response()->json($attendance->load(['employee', 'shift', 'breaks']));
    }

    public function update(AttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update($request->validated());
        
        // Recalculate work minutes and update status
        $attendance->update([
            'work_minutes' => $attendance->calculateWorkMinutes(),
        ]);
        $attendance->updateStatus();

        return response()->json([
            'message' => 'Attendance updated successfully',
            'attendance' => $attendance->load(['employee', 'shift'])
        ]);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json(['message' => 'Attendance deleted successfully']);
    }
}




