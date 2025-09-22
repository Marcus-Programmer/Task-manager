<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->route('task')?->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,in_progress,done',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'title.min' => 'Task title must be at least 3 characters long.',
            'title.max' => 'Task title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'status.in' => 'Status must be one of: pending, in_progress, done.',
        ];
    }
}
