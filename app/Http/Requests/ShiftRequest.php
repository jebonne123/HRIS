<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_time_1' => ['nullable', 'date_format:H:i:s'],
            'end_time_1' => ['nullable', 'date_format:H:i:s', 'after:start_time_1'],
            'start_time_2' => ['nullable', 'date_format:H:i:s'],
            'end_time_2' => ['nullable', 'date_format:H:i:s', 'after:start_time_2'],
            'bell_enabled' => ['boolean'],
            'allow_late_minutes' => ['integer', 'min:0', 'max:1440'],
            'allow_early_minutes' => ['integer', 'min:0', 'max:1440'],
            'rest_days' => ['integer', 'min:0', 'max:7'],
            'enable_ot' => ['boolean'],
            'ot_start_minutes' => ['integer', 'min:0', 'max:1440'],
            'ot_valid_minutes' => ['integer', 'min:1', 'max:1440'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Shift name is required',
            'start_time_1.date_format' => 'Start time 1 must be in HH:MM:SS format',
            'end_time_1.after' => 'End time 1 must be after start time 1',
            'start_time_2.date_format' => 'Start time 2 must be in HH:MM:SS format',
            'end_time_2.after' => 'End time 2 must be after start time 2',
            'allow_late_minutes.integer' => 'Allow late minutes must be a number',
            'allow_late_minutes.min' => 'Allow late minutes must be at least 0',
            'allow_late_minutes.max' => 'Allow late minutes cannot exceed 1440',
            'allow_early_minutes.integer' => 'Allow early minutes must be a number',
            'allow_early_minutes.min' => 'Allow early minutes must be at least 0',
            'allow_early_minutes.max' => 'Allow early minutes cannot exceed 1440',
            'rest_days.integer' => 'Rest days must be a number',
            'rest_days.min' => 'Rest days must be at least 0',
            'rest_days.max' => 'Rest days cannot exceed 7',
            'ot_start_minutes.integer' => 'OT start minutes must be a number',
            'ot_start_minutes.min' => 'OT start minutes must be at least 0',
            'ot_start_minutes.max' => 'OT start minutes cannot exceed 1440',
            'ot_valid_minutes.integer' => 'OT valid minutes must be a number',
            'ot_valid_minutes.min' => 'OT valid minutes must be at least 1',
            'ot_valid_minutes.max' => 'OT valid minutes cannot exceed 1440',
        ];
    }
}




