<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'work_minutes' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:present,absent,late'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required',
            'employee_id.exists' => 'Selected employee is invalid',
            'shift_id.required' => 'Shift is required',
            'shift_id.exists' => 'Selected shift is invalid',
            'date.required' => 'Date is required',
            'date.date' => 'Date must be a valid date',
            'check_out.after' => 'Check out time must be after check in time',
            'work_minutes.integer' => 'Work minutes must be a number',
            'work_minutes.min' => 'Work minutes must be at least 0',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be present, absent, or late',
        ];
    }
}




