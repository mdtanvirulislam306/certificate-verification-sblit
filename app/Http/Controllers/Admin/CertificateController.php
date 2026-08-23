<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCertificateRequest;
use App\Models\Certificate;
use App\Models\Course;
use App\Services\CertificateMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CertificateController extends Controller
{
    public function __construct(private CertificateMetricsService $metrics)
    {
    }

    public function index(Request $request): Response
    {
        $certificates = Certificate::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                        ->orWhere('certificate_code', 'like', "%{$search}%")
                        ->orWhere('course_name', 'like', "%{$search}%")
                        ->orWhere('batch', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Certificates/Index', [
            'certificates' => $certificates,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function generate(Certificate $certificate): Response
    {
        $certificate->load(['subjects', 'assessments', 'skills', 'milestones']);
        $payload = $certificate->toVerifyPayload();

        return Inertia::render('Admin/Certificates/Generate', [
            'certificate' => [
                ...$payload['certificate'],
                'grade' => $payload['metrics']['grade'],
                'overallScore' => $payload['metrics']['overallScore'],
                'status' => $payload['metrics']['status'],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Certificates/Form', [
            'certificate' => null,
            'defaults' => $this->emptyForm(),
            'courses' => $this->courseOptions(),
        ]);
    }

    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        $data = $this->baseData($request);
        $certificate = Certificate::create($data);

        $this->storeUploads($request, $certificate);
        $this->metrics->syncRelated(
            $certificate,
            $request->input('subjects', []),
            $request->input('assessments', []),
            $request->input('milestones', []),
        );

        return redirect()
            ->route('admin.certificates.generate', $certificate)
            ->with('success', 'Certificate saved. Preview is ready to download.');
    }

    public function edit(Certificate $certificate): Response
    {
        $certificate->load(['subjects', 'assessments', 'skills', 'milestones']);

        return Inertia::render('Admin/Certificates/Form', [
            'certificate' => $certificate,
            'courses' => $this->courseOptions(),
            'defaults' => [
                ...$certificate->only([
                    'certificate_code',
                    'student_name',
                    'course_name',
                    'course_id',
                    'batch',
                    'duration',
                    'signature_name',
                    'overall_score',
                    'grade',
                    'attendance',
                    'status',
                    'verify_url_display',
                    'is_published',
                ]),
                'enrollment_date' => optional($certificate->enrollment_date)->format('Y-m-d'),
                'completion_date' => optional($certificate->completion_date)->format('Y-m-d'),
                'issue_date' => optional($certificate->issue_date)->format('Y-m-d'),
                'logo_url' => $certificate->logoUrl(),
                'signature_image_url' => $certificate->signatureImageUrl(),
                'certificate_file_url' => $certificate->certificateFileUrl(),
                'subjects' => $certificate->subjects->map(fn ($s) => [
                    'name' => $s->name,
                    'obtained' => $s->obtained,
                    'total' => $s->total,
                    'grade' => $s->grade,
                ])->values()->all(),
                'assessments' => $certificate->assessments->map(fn ($a) => [
                    'name' => $a->name,
                    'obtained' => $a->obtained,
                    'total' => $a->total,
                ])->values()->all(),
                'skills' => $certificate->skills->map(fn ($s) => [
                    'name' => $s->name,
                    'percentage' => $s->percentage,
                ])->values()->all(),
                'milestones' => $certificate->milestones->map(fn ($m) => [
                    'label' => $m->label,
                    'date_label' => $m->date_label,
                ])->values()->all(),
            ],
        ]);
    }

    public function update(StoreCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->update($this->baseData($request));
        $this->storeUploads($request, $certificate);
        $this->metrics->syncRelated(
            $certificate->fresh(),
            $request->input('subjects', []),
            $request->input('assessments', []),
            $request->input('milestones', []),
        );

        return redirect()
            ->route('admin.certificates.generate', $certificate)
            ->with('success', 'Certificate updated. Preview is ready to download.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        foreach (['logo', 'signature_image', 'certificate_file'] as $field) {
            if ($certificate->{$field}) {
                Storage::disk('public')->delete($certificate->{$field});
            }
        }

        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Certificate deleted.');
    }

    private function baseData(StoreCertificateRequest $request): array
    {
        $course = $request->course_id
            ? Course::query()->find($request->course_id)
            : null;

        return [
            'certificate_code' => $request->certificate_code,
            'student_name' => $request->student_name,
            'course_id' => $course?->id,
            'course_name' => $course?->name ?: $request->course_name,
            'batch' => $request->batch,
            'duration' => $request->duration ?: $course?->duration,
            'enrollment_date' => $request->enrollment_date,
            'completion_date' => $request->completion_date,
            'issue_date' => $request->issue_date,
            'signature_name' => $request->signature_name,
            'overall_score' => $request->overall_score,
            'grade' => $request->grade,
            'attendance' => $request->attendance,
            'status' => $request->status ?: 'Completed',
            'verify_url_display' => $request->verify_url_display,
            'is_published' => $request->boolean('is_published', true),
        ];
    }

    private function storeUploads(StoreCertificateRequest $request, Certificate $certificate): void
    {
        $updates = [];

        foreach ([
            'logo' => 'certificates/logos',
            'signature_image' => 'certificates/signatures',
            'certificate_file' => 'certificates/files',
        ] as $field => $folder) {
            if ($request->hasFile($field)) {
                if ($certificate->{$field}) {
                    Storage::disk('public')->delete($certificate->{$field});
                }
                $updates[$field] = $request->file($field)->store($folder, 'public');
            }
        }

        if ($updates !== []) {
            $certificate->update($updates);
        }
    }

    private function emptyForm(): array
    {
        return [
            'certificate_code' => '',
            'student_name' => '',
            'course_id' => null,
            'course_name' => '',
            'batch' => '',
            'duration' => '',
            'enrollment_date' => '',
            'completion_date' => '',
            'issue_date' => '',
            'signature_name' => 'Skill Builders',
            'overall_score' => null,
            'grade' => '',
            'attendance' => 100,
            'status' => 'Completed',
            'verify_url_display' => 'skillbuilders.edu.bd/verify',
            'is_published' => true,
            'subjects' => [
                ['name' => '', 'obtained' => 0, 'total' => 100, 'grade' => ''],
            ],
            'assessments' => [
                ['name' => '', 'obtained' => 0, 'total' => 100],
            ],
            'skills' => [],
            'milestones' => [
                ['label' => 'Enrollment', 'date_label' => ''],
                ['label' => 'Foundation', 'date_label' => ''],
                ['label' => 'Advanced Training', 'date_label' => ''],
                ['label' => 'Assessments', 'date_label' => ''],
                ['label' => 'Final Project', 'date_label' => ''],
                ['label' => 'Certificate Issued', 'date_label' => ''],
            ],
        ];
    }

    private function courseOptions(): array
    {
        return Course::query()
            ->with(['modules', 'assessments'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn (Course $course) => $course->toFormTemplate())
            ->values()
            ->all();
    }
}
