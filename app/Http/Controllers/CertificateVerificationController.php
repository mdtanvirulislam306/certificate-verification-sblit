<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Inertia\Inertia;
use Inertia\Response;

class CertificateVerificationController extends Controller
{
    public function show(string $code): Response
    {
        $certificate = Certificate::query()
            ->with(['subjects', 'assessments', 'skills', 'milestones'])
            ->where('certificate_code', $code)
            ->where('is_published', true)
            ->firstOrFail();

        return Inertia::render('Verify/Result', $certificate->toVerifyPayload());
    }
}
