<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:shifts,slug,' . $this->route('id'),
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_minutes' => 'nullable|integer|min:0',
            'half_day_cutoff' => 'nullable',
            'description' => 'nullable|max:1000',
            'color' => 'nullable|max:20',
            'status' => 'nullable|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the shift name.',
            'name.max' => 'Shift name must not exceed 255 characters.',
            'start_time.required' => 'Please enter the start time.',
            'end_time.required' => 'Please enter the end time.',
            'grace_minutes.integer' => 'Grace minutes must be a whole number.',
            'grace_minutes.min' => 'Grace minutes cannot be negative.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
