<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.max' => 'First name must not exceed 255 characters.',
            'last_name.required' => 'Please enter your last name.',
            'last_name.max' => 'Last name must not exceed 255 characters.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPG, JPEG, or PNG file.',
            'avatar.max' => 'Avatar size must not exceed 2 MB.',
        ];
    }
}
