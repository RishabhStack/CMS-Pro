<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required|max:5000',
            'type' => 'required|max:50',
            'priority' => 'required|max:50',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter the announcement title.',
            'title.max' => 'Title must not exceed 255 characters.',
            'content.required' => 'Please enter the announcement content.',
            'content.max' => 'Content must not exceed 5000 characters.',
            'type.required' => 'Please select the announcement type.',
            'type.max' => 'Type must not exceed 50 characters.',
            'priority.required' => 'Please select the priority level.',
            'priority.max' => 'Priority must not exceed 50 characters.',
            'published_at.date' => 'Please enter a valid publish date.',
            'expires_at.date' => 'Please enter a valid expiry date.',
            'expires_at.after' => 'Expiry date must be after the publish date.',
        ];
    }
}
