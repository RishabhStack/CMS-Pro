<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'days_per_year' => 'required|integer|min:0|max:365',
            'carry_forward' => 'boolean',
            'max_carry_forward' => 'integer|min:0',
            'color' => 'nullable|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the leave type name.',
            'name.max' => 'Leave type name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'days_per_year.required' => 'Please enter the number of days per year.',
            'days_per_year.integer' => 'Days per year must be a whole number.',
            'days_per_year.min' => 'Days per year cannot be negative.',
            'days_per_year.max' => 'Days per year cannot exceed 365.',
            'carry_forward.boolean' => 'Carry forward must be true or false.',
            'max_carry_forward.integer' => 'Maximum carry forward must be a whole number.',
            'max_carry_forward.min' => 'Maximum carry forward cannot be negative.',
            'color.max' => 'Color must not exceed 20 characters.',
        ];
    }
}
