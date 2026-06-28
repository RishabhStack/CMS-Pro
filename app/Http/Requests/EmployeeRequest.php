<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $emailRule = 'required|email|max:255';
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $employee = \App\Models\Employee::find($this->route('id'));
            if ($employee && $employee->user) {
                $emailRule .= '|unique:users,email,' . $employee->user->id;
            }
        } else {
            $emailRule .= '|unique:users,email';
        }

        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => $emailRule,
            'phone' => 'nullable|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'status' => 'nullable|in:active,inactive,terminated,resigned',
            'reporting_to_id' => 'nullable|exists:employees,id',
            'joining_date' => 'nullable|date',
            'employment_type' => 'nullable|max:50',
            'work_shift' => 'nullable|max:50',
            'work_location' => 'nullable|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter the first name.',
            'first_name.max' => 'First name must not exceed 255 characters.',
            'last_name.required' => 'Please enter the last name.',
            'last_name.max' => 'Last name must not exceed 255 characters.',
            'email.required' => 'Please enter the email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'email.max' => 'Email must not exceed 255 characters.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'department_id.exists' => 'Selected department does not exist.',
            'designation_id.exists' => 'Selected designation does not exist.',
            'reporting_to_id.exists' => 'Selected reporting manager does not exist.',
            'joining_date.date' => 'Please enter a valid joining date.',
            'employment_type.max' => 'Employment type must not exceed 50 characters.',
            'work_shift.max' => 'Work shift must not exceed 50 characters.',
            'work_location.max' => 'Work location must not exceed 255 characters.',
        ];
    }
}
