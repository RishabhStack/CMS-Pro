<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftSwapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'to_employee_id' => 'required|exists:employees,id',
            'shift_assignment_id' => 'required|exists:shift_assignments,id',
            'date' => 'required|date',
            'reason' => 'required|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'to_employee_id.required' => 'Please select an employee to swap with.',
            'to_employee_id.exists' => 'Selected employee does not exist.',
            'shift_assignment_id.required' => 'Please select a shift assignment.',
            'shift_assignment_id.exists' => 'Selected shift assignment does not exist.',
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
            'reason.required' => 'Please provide a reason for the swap.',
            'reason.max' => 'Reason must not exceed 1000 characters.',
        ];
    }
}
