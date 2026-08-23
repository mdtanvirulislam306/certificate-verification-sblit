<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_code',
        'student_name',
        'course_name',
        'course_id',
        'batch',
        'duration',
        'enrollment_date',
        'completion_date',
        'issue_date',
        'signature_name',
        'signature_image',
        'logo',
        'certificate_file',
        'overall_score',
        'grade',
        'attendance',
        'status',
        'verify_url_display',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'completion_date' => 'date',
            'issue_date' => 'date',
            'overall_score' => 'float',
            'is_published' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(CertificateSubject::class)->orderBy('sort_order');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(CertificateAssessment::class)->orderBy('sort_order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CertificateSkill::class)->orderBy('sort_order');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(CertificateMilestone::class)->orderBy('sort_order');
    }

    public function verifyUrl(): string
    {
        return url('/verify/'.$this->certificate_code);
    }

    public function logoUrl(): ?string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : asset('images/logo.jpg');
    }

    public function signatureImageUrl(): ?string
    {
        return $this->signature_image
            ? Storage::disk('public')->url($this->signature_image)
            : null;
    }

    public function certificateFileUrl(): ?string
    {
        return $this->certificate_file
            ? Storage::disk('public')->url($this->certificate_file)
            : null;
    }

    public function toVerifyPayload(): array
    {
        return [
            'certificate' => [
                'id' => $this->certificate_code,
                'studentName' => $this->student_name,
                'courseName' => $this->course_name,
                'batch' => $this->batch,
                'duration' => $this->duration,
                'enrollmentDate' => optional($this->enrollment_date)->format('d M Y'),
                'completionDate' => optional($this->completion_date)->format('d M Y'),
                'issueDate' => optional($this->issue_date)->format('d M Y'),
                'signatureName' => $this->signature_name,
                'signatureImage' => $this->signatureImageUrl(),
                'logo' => $this->logoUrl(),
                'certificateFile' => $this->certificateFileUrl(),
                'verifyUrl' => $this->verifyUrl(),
                'verifyUrlDisplay' => $this->verify_url_display ?: 'skillbuilders.edu.bd/verify',
            ],
            'metrics' => [
                'overallScore' => (float) $this->overall_score,
                'grade' => $this->grade,
                'attendance' => (int) $this->attendance,
                'status' => $this->status,
            ],
            'subjects' => $this->subjects->map(fn ($s) => [
                'name' => $s->name,
                'obtained' => $s->obtained,
                'total' => $s->total,
                'percentage' => $s->percentage,
                'grade' => $s->grade,
            ])->values()->all(),
            'assessments' => $this->assessments->map(fn ($a) => [
                'name' => $a->name,
                'obtained' => $a->obtained,
                'total' => $a->total,
                'percentage' => $a->percentage,
            ])->values()->all(),
            'skills' => $this->skills->map(fn ($s) => [
                'name' => $s->name,
                'percentage' => $s->percentage,
            ])->values()->all(),
            'milestones' => $this->milestones->map(fn ($m) => [
                'label' => $m->label,
                'date' => $m->date_label,
            ])->values()->all(),
        ];
    }
}
