<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'manager_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the department name.',
            'name.max' => 'Department name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'manager_id.exists' => 'Selected manager does not exist.',
        ];
    }
}
