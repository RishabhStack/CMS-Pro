<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'task_name' => 'nullable|max:255',
            'description' => 'nullable|max:1000',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'total_hours' => 'required|numeric|min:0|max:24',
            'is_billable' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
            'task_name.max' => 'Task name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'total_hours.required' => 'Please enter total hours.',
            'total_hours.numeric' => 'Total hours must be a number.',
            'total_hours.min' => 'Total hours cannot be negative.',
            'total_hours.max' => 'Total hours cannot exceed 24.',
            'project_id.exists' => 'Selected project does not exist.',
        ];
    }
}
