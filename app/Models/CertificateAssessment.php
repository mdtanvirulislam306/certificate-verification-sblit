<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateAssessment extends Model
{
    protected $fillable = [
        'certificate_id',
        'name',
        'obtained',
        'total',
        'percentage',
        'sort_order',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
