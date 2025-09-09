<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start'],
            'status' => ['required', 'in:draft,generated,approved,paid'],
            'total_gross' => ['nullable', 'numeric', 'min:0'],
            'total_deductions' => ['nullable', 'numeric', 'min:0'],
            'total_net' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'period_start.required' => 'Period start date is required',
            'period_start.date' => 'Period start must be a valid date',
            'period_end.required' => 'Period end date is required',
            'period_end.date' => 'Period end must be a valid date',
            'period_end.after' => 'Period end must be after period start',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be draft, generated, approved, or paid',
            'total_gross.numeric' => 'Total gross must be a number',
            'total_gross.min' => 'Total gross must be at least 0',
            'total_deductions.numeric' => 'Total deductions must be a number',
            'total_deductions.min' => 'Total deductions must be at least 0',
            'total_net.numeric' => 'Total net must be a number',
        ];
    }
}




