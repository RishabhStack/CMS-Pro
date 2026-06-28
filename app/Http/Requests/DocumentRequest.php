<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required|max:255',
            'type' => 'required|max:100',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|max:1000',
            'expiry_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the document name.',
            'name.max' => 'Document name must not exceed 255 characters.',
            'type.required' => 'Please enter the document type.',
            'type.max' => 'Document type must not exceed 100 characters.',
            'file.required' => 'Please upload a file.',
            'file.file' => 'Please upload a valid file.',
            'file.mimes' => 'File must be a PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'file.max' => 'File size must not exceed 10 MB.',
            'description.max' => 'Description must not exceed 1000 characters.',
            'expiry_date.date' => 'Please enter a valid expiry date.',
        ];
    }
}
