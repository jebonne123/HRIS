<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Always include key relations used by the UI
        $with = ['department', 'designation', 'employmentStatus', 'primaryShift'];

        // Optional: allow comma-separated "with" param to extend eager loads
        if ($request->filled('with')) {
            $extra = collect(explode(',', $request->get('with')))
                ->map(fn ($s) => trim($s))
                ->filter()
                ->all();
            $with = array_values(array_unique(array_merge($with, $extra)));
        }

        $query = Employee::with($with);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('fp_id', 'like', "%{$search}%")
                  ->orWhere('card_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->integer('designation_id'));
        }

        if ($request->filled('employment_status_id')) {
            $query->where('employment_status_id', $request->integer('employment_status_id'));
        }

        if ($request->filled('shift_id')) {
            $shiftId = $request->integer('shift_id');
            $query->where(function($q) use ($shiftId) {
                $q->where('primary_shift_id', $shiftId)
                  ->orWhereHas('shifts', function($sq) use ($shiftId) {
                      $sq->where('shifts.id', $shiftId);
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Date hired filter: include employees whose hire_date OR created_at falls in the range
        if ($request->filled('hired_from') || $request->filled('hired_to')) {
            $from = $request->filled('hired_from') ? Carbon::parse($request->get('hired_from'))->startOfDay() : null;
            $to = $request->filled('hired_to') ? Carbon::parse($request->get('hired_to'))->endOfDay() : null;

            $query->where(function ($q) use ($from, $to) {
                if ($from && $to) {
                    $q->whereBetween('hire_date', [$from->toDateString(), $to->toDateString()])
                      ->orWhereBetween('created_at', [$from, $to]);
                } elseif ($from) {
                    $q->whereDate('hire_date', '>=', $from->toDateString())
                      ->orWhere('created_at', '>=', $from);
                } elseif ($to) {
                    $q->whereDate('hire_date', '<=', $to->toDateString())
                      ->orWhere('created_at', '<=', $to);
                }
            });
        }

        $perPage = (int) $request->get('per_page', 15);
        $employees = $query->paginate($perPage);

        return response()->json($employees);
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo_path'] = $path;
        }

        $employee = Employee::create($data);

        // Assign shifts if provided
        if ($request->has('shift_ids')) {
            $shiftData = [];
            foreach ($request->shift_ids as $shiftId) {
                $shiftData[$shiftId] = [
                    'effective_from' => now(),
                    'effective_until' => null,
                ];
            }
            $employee->shifts()->attach($shiftData);
        }

        return response()->json([
            'message' => 'Employee created successfully',
            'employee' => $employee->load(['department', 'shifts'])
        ], 201);
    }

    public function show(Employee $employee)
    {
        return response()->json($employee->load(['department', 'shifts', 'attendances']));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
            
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo_path'] = $path;
        }

        $employee->update($data);

        // Update shifts if provided
        if ($request->has('shift_ids')) {
            $employee->shifts()->detach();
            $shiftData = [];
            foreach ($request->shift_ids as $shiftId) {
                $shiftData[$shiftId] = [
                    'effective_from' => now(),
                    'effective_until' => null,
                ];
            }
            $employee->shifts()->attach($shiftData);
        }

        return response()->json([
            'message' => 'Employee updated successfully',
            'employee' => $employee->load(['department', 'shifts'])
        ]);
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo_path) {
            Storage::disk('public')->delete($employee->photo_path);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function departments()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    public function shifts()
    {
        $shifts = Shift::all();
        return response()->json($shifts);
    }
}



