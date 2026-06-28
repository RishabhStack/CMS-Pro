<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:projects,slug,' . $this->route('id'),
            'description' => 'nullable|max:1000',
            'status' => 'nullable|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the project name.',
            'name.max' => 'Project name must not exceed 255 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
