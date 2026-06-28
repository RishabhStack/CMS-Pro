<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|current_password',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
            'password_confirmation' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Please enter your current password.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your new password.',
        ];
    }
}
