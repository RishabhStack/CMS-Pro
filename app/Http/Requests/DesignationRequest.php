<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'Selected department does not exist.',
            'name.required' => 'Please enter the designation name.',
            'name.max' => 'Designation name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
