<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:companies,email|max:255',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:500',
            'city' => 'nullable|max:100',
            'state' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'postal_code' => 'nullable|max:20',
            'timezone' => 'nullable|max:50',
            'currency' => 'nullable|max:10',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the company name.',
            'name.max' => 'Company name must not exceed 255 characters.',
            'email.required' => 'Please enter the company email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered by another company.',
            'email.max' => 'Email must not exceed 255 characters.',
            'phone.max' => 'Phone number must not exceed 20 characters.',
            'address.max' => 'Address must not exceed 500 characters.',
            'city.max' => 'City must not exceed 100 characters.',
            'state.max' => 'State must not exceed 100 characters.',
            'country.max' => 'Country must not exceed 100 characters.',
            'postal_code.max' => 'Postal code must not exceed 20 characters.',
            'timezone.max' => 'Timezone must not exceed 50 characters.',
            'currency.max' => 'Currency must not exceed 10 characters.',
            'website.url' => 'Please enter a valid URL.',
            'website.max' => 'Website URL must not exceed 255 characters.',
            'tax_number.max' => 'Tax number must not exceed 50 characters.',
        ];
    }
}
