<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the role name.',
            'name.max' => 'Role name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.exists' => 'One or more selected permissions do not exist.',
        ];
    }
}
