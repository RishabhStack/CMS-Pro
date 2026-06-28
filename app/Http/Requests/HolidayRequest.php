<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'date' => 'required|date',
            'type' => 'required|max:50',
            'description' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the holiday name.',
            'name.max' => 'Holiday name must not exceed 255 characters.',
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
            'type.required' => 'Please enter the holiday type.',
            'type.max' => 'Holiday type must not exceed 50 characters.',
            'description.max' => 'Description must not exceed 1000 characters.',
        ];
    }
}
