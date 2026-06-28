<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'key' => 'required|max:255',
            'value' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'Please enter the setting key.',
            'key.max' => 'Setting key must not exceed 255 characters.',
        ];
    }
}
