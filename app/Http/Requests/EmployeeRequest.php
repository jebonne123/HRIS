<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'fp_id' => ['nullable', 'string', 'max:255', 'unique:employees,fp_id,' . ($this->employee?->id ?? '')],
            'card_id' => ['nullable', 'string', 'max:255', 'unique:employees,card_id,' . ($this->employee?->id ?? '')],
            'pwd' => ['nullable', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation_id' => ['required', 'exists:designations,id'],
            'hire_date' => ['required', 'date'],
            'base_salary' => ['nullable', 'numeric', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'shift_ids' => ['nullable', 'array'],
            'shift_ids.*' => ['exists:shifts,id'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'full_name.required' => 'Full name is required',
            'fp_id.unique' => 'Fingerprint ID must be unique',
            'card_id.unique' => 'Card ID must be unique',
            'department_id.exists' => 'Selected department is invalid',
            'hire_date.date' => 'Hire date must be a valid date',
            'photo.image' => 'Photo must be an image file',
            'photo.mimes' => 'Photo must be a JPEG, PNG, JPG, or GIF file',
            'photo.max' => 'Photo size must not exceed 2MB',
            'status.in' => 'Status must be either active or inactive',
            'shift_ids.array' => 'Shifts must be an array',
            'shift_ids.*.exists' => 'One or more selected shifts are invalid',
        ];
    }
}



