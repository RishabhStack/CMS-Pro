<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2100',
            'basic_salary' => 'required|numeric|min:0',
            'total_earnings' => 'required|numeric|min:0',
            'total_deductions' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'month.required' => 'Please select the month.',
            'month.integer' => 'Month must be a number.',
            'month.between' => 'Month must be between 1 and 12.',
            'year.required' => 'Please enter the year.',
            'year.integer' => 'Year must be a number.',
            'year.min' => 'Year must be 2020 or later.',
            'year.max' => 'Year must be 2100 or earlier.',
            'basic_salary.required' => 'Please enter the basic salary.',
            'basic_salary.numeric' => 'Basic salary must be a number.',
            'basic_salary.min' => 'Basic salary cannot be negative.',
            'total_earnings.required' => 'Please enter total earnings.',
            'total_earnings.numeric' => 'Total earnings must be a number.',
            'total_earnings.min' => 'Total earnings cannot be negative.',
            'total_deductions.required' => 'Please enter total deductions.',
            'total_deductions.numeric' => 'Total deductions must be a number.',
            'total_deductions.min' => 'Total deductions cannot be negative.',
            'net_salary.required' => 'Please enter the net salary.',
            'net_salary.numeric' => 'Net salary must be a number.',
            'net_salary.min' => 'Net salary cannot be negative.',
        ];
    }
}
