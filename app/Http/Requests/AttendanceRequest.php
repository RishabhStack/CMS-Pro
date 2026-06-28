<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'required',
            'clock_out' => 'nullable',
            'status' => 'nullable|in:present,absent,late,half-day,leave',
            'notes' => 'nullable|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
            'clock_in.required' => 'Please enter the clock in time.',
            'notes.max' => 'Notes must not exceed 500 characters.',
        ];
    }
}
