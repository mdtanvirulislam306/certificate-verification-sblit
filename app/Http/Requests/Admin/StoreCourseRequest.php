<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses', 'name')->ignore($courseId),
            ],
            'duration' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
            'modules' => ['nullable', 'array'],
            'modules.*.name' => ['nullable', 'string', 'max:255'],
            'modules.*.default_total' => ['nullable', 'integer', 'min:1'],
            'assessments' => ['nullable', 'array'],
            'assessments.*.name' => ['nullable', 'string', 'max:255'],
            'assessments.*.default_total' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
