<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return response()->json($shifts);
    }

    public function store(ShiftRequest $request)
    {
        $shift = Shift::create($request->validated());

        return response()->json([
            'message' => 'Shift created successfully',
            'shift' => $shift
        ], 201);
    }

    public function show(Shift $shift)
    {
        return response()->json($shift->load('employees'));
    }

    public function update(ShiftRequest $request, Shift $shift)
    {
        $shift->update($request->validated());

        return response()->json([
            'message' => 'Shift updated successfully',
            'shift' => $shift
        ]);
    }

    public function destroy(Shift $shift)
    {
        // Check if shift is assigned to any employees
        if ($shift->employees()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete shift that is assigned to employees'
            ], 422);
        }

        $shift->delete();

        return response()->json(['message' => 'Shift deleted successfully']);
    }
}




