<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'No account found with this email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }
}
