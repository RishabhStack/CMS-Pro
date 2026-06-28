<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'date' => 'required|date',
            'notes' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'shift_id.required' => 'Please select a shift.',
            'shift_id.exists' => 'Selected shift does not exist.',
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
            'notes.max' => 'Notes must not exceed 1000 characters.',
        ];
    }
}
