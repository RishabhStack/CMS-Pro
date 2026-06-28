<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryComponentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'type' => 'required|in:earning,deduction',
            'value_type' => 'required|in:fixed,percentage',
            'default_value' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the component name.',
            'name.max' => 'Component name must not exceed 255 characters.',
            'type.required' => 'Please select the component type.',
            'type.in' => 'Type must be either earning or deduction.',
            'value_type.required' => 'Please select the value type.',
            'value_type.in' => 'Value type must be either fixed or percentage.',
            'default_value.required' => 'Please enter the default value.',
            'default_value.numeric' => 'Default value must be a number.',
            'default_value.min' => 'Default value cannot be negative.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
