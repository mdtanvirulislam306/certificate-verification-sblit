<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $certificateId = $this->route('certificate')?->id;

        return [
            'certificate_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('certificates', 'certificate_code')->ignore($certificateId),
            ],
            'student_name' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'course_name' => ['nullable', 'string', 'max:255', 'required_without:course_id'],
            'batch' => ['nullable', 'string', 'max:100'],
            'duration' => ['nullable', 'string', 'max:100'],
            'enrollment_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'issue_date' => ['nullable', 'date'],
            'signature_name' => ['nullable', 'string', 'max:255'],
            'signature_image' => ['nullable', 'image', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'overall_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grade' => ['nullable', 'string', 'max:10'],
            'attendance' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', 'string', 'max:50'],
            'verify_url_display' => ['nullable', 'string', 'max:255'],
            'is_published' => ['sometimes', 'boolean'],
            'subjects' => ['nullable', 'array'],
            'subjects.*.name' => ['nullable', 'string', 'max:255'],
            'subjects.*.obtained' => ['nullable', 'integer', 'min:0'],
            'subjects.*.total' => ['nullable', 'integer', 'min:1'],
            'subjects.*.grade' => ['nullable', 'string', 'max:10'],
            'assessments' => ['nullable', 'array'],
            'assessments.*.name' => ['nullable', 'string', 'max:255'],
            'assessments.*.obtained' => ['nullable', 'integer', 'min:0'],
            'assessments.*.total' => ['nullable', 'integer', 'min:1'],
            'milestones' => ['nullable', 'array'],
            'milestones.*.label' => ['nullable', 'string', 'max:255'],
            'milestones.*.date_label' => ['nullable', 'string', 'max:100'],
        ];
    }
}
