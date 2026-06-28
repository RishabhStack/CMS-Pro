<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.required' => 'Please select a leave type.',
            'leave_type_id.exists' => 'Selected leave type does not exist.',
            'start_date.required' => 'Please select a start date.',
            'start_date.date' => 'Please enter a valid start date.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.required' => 'Please select an end date.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
            'reason.required' => 'Please provide a reason for the leave.',
            'reason.max' => 'Reason must not exceed 2000 characters.',
        ];
    }
}
