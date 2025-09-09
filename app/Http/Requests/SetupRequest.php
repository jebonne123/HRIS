<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_phone' => ['nullable', 'string', 'max:20'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:50'],

            // Optional default shift configuration for first-time setup
            'shift_name' => ['nullable', 'string', 'max:255'],
            'start_time_1' => ['nullable', 'date_format:H:i'],
            'end_time_1' => ['nullable', 'date_format:H:i'],
            'start_time_2' => ['nullable', 'date_format:H:i'],
            'end_time_2' => ['nullable', 'date_format:H:i'],
            'bell_enabled' => ['nullable', 'boolean'],
            'allow_late_minutes' => ['nullable', 'integer', 'min:0'],
            'allow_early_minutes' => ['nullable', 'integer', 'min:0'],
            'rest_days' => ['nullable', 'integer', 'min:0', 'max:7'],
            'enable_ot' => ['nullable', 'boolean'],
            // Validate OT minutes only when overtime is enabled
            'ot_start_minutes' => ['nullable', 'integer', 'min:0', 'exclude_unless:enable_ot,true'],
            'ot_valid_minutes' => ['nullable', 'integer', 'min:1', 'exclude_unless:enable_ot,true'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required',
            'company_name.max' => 'Company name must not exceed 255 characters',
            'company_address.max' => 'Company address must not exceed 500 characters',
            'company_phone.max' => 'Company phone must not exceed 20 characters',
            'company_email.email' => 'Please enter a valid email address',
            'company_email.max' => 'Company email must not exceed 255 characters',
            'timezone.max' => 'Timezone must not exceed 50 characters',

            'shift_name.max' => 'Shift name must not exceed 255 characters',
            'start_time_1.date_format' => 'Start time 1 must be in HH:MM format',
            'end_time_1.date_format' => 'End time 1 must be in HH:MM format',
            'start_time_2.date_format' => 'Start time 2 must be in HH:MM format',
            'end_time_2.date_format' => 'End time 2 must be in HH:MM format',
            'allow_late_minutes.integer' => 'Allow late must be a number of minutes',
            'allow_early_minutes.integer' => 'Allow early must be a number of minutes',
            'rest_days.integer' => 'Rest days must be a number between 0 and 7',
            'rest_days.max' => 'Rest days must be at most 7',
            'ot_start_minutes.integer' => 'OT start must be a number of minutes',
            'ot_valid_minutes.integer' => 'OT valid must be a number of minutes',
        ];
    }
}


