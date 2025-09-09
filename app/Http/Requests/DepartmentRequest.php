<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,' . $departmentId],
            'manager_employee_id' => ['nullable', 'exists:employees,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required',
            'name.max' => 'Department name must not exceed 255 characters',
            'code.required' => 'Department code is required',
            'code.max' => 'Department code must not exceed 50 characters',
            'code.unique' => 'Department code must be unique',
            'manager_employee_id.exists' => 'Selected manager is invalid',
        ];
    }
}




