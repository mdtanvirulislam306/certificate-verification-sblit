<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Inertia\Inertia;
use Inertia\Response;

class CertificateVerificationController extends Controller
{
    public function show(string $code): Response
    {
        $normalizedCode = trim($code);

        $certificate = Certificate::query()
            ->with(['subjects', 'assessments', 'skills', 'milestones'])
            ->where('certificate_code', $normalizedCode)
            ->first();

        if ($certificate && $certificate->is_published) {
            return Inertia::render('Verify/Result', $certificate->toVerifyPayload());
        }

        return Inertia::render('Verify/NotFound', [
            'code' => $normalizedCode,
            'reason' => $certificate ? 'inactive' : 'not_found',
        ]);
    }
}
