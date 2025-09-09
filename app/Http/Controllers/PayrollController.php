<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollRequest;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with(['generator']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('period_start')) {
            $query->where('period_start', '>=', $request->period_start);
        }

        if ($request->has('period_end')) {
            $query->where('period_end', '<=', $request->period_end);
        }

        $payrolls = $query->orderBy('period_start', 'desc')->paginate(15);

        return response()->json($payrolls);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ]);

        $periodStart = Carbon::parse($request->period_start);
        $periodEnd = Carbon::parse($request->period_end);

        // Check if payroll already exists for this period
        $existingPayroll = Payroll::where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->first();

        if ($existingPayroll) {
            return response()->json([
                'message' => 'Payroll already exists for this period'
            ], 422);
        }

        // Create payroll record
        $payroll = Payroll::create([
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'generated_by' => Auth::id(),
            'status' => 'draft',
        ]);

        // Generate payroll items for all active employees
        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            // Calculate work hours for the period
            $workMinutes = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$periodStart, $periodEnd])
                ->sum('work_minutes');

            // Basic salary calculation (placeholder - implement your salary logic)
            $basicSalary = 5000; // Default basic salary
            $allowances = []; // Implement allowances logic
            $deductions = []; // Implement deductions logic
            $netPay = $basicSalary;

            $payroll->items()->create([
                'employee_id' => $employee->id,
                'basic_salary' => $basicSalary,
                'allowances_json' => $allowances,
                'deductions_json' => $deductions,
                'net_pay' => $netPay,
            ]);
        }

        // Calculate totals
        $totalGross = $payroll->items->sum('basic_salary');
        $totalDeductions = $payroll->items->sum(function ($item) {
            return collect($item->deductions_json)->sum('amount');
        });
        $totalNet = $totalGross - $totalDeductions;

        $payroll->update([
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
        ]);

        return response()->json([
            'message' => 'Payroll generated successfully',
            'payroll' => $payroll->load(['items.employee'])
        ], 201);
    }

    public function show(Payroll $payroll)
    {
        return response()->json($payroll->load(['items.employee', 'generator']));
    }

    public function update(PayrollRequest $request, Payroll $payroll)
    {
        $payroll->update($request->validated());

        return response()->json([
            'message' => 'Payroll updated successfully',
            'payroll' => $payroll->load(['items.employee'])
        ]);
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return response()->json([
                'message' => 'Cannot delete payroll that is not in draft status'
            ], 422);
        }

        $payroll->delete();

        return response()->json(['message' => 'Payroll deleted successfully']);
    }

    public function download(Payroll $payroll)
    {
        // Placeholder for PDF/CSV download functionality
        return response()->json([
            'message' => 'Download functionality not implemented yet',
            'payroll_id' => $payroll->id
        ]);
    }
}




