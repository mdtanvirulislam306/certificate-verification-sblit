<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(CourseAssessment::class)->orderBy('sort_order');
    }

    public function toFormTemplate(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'duration' => $this->duration,
            'subjects' => $this->modules->map(fn ($m) => [
                'name' => $m->name,
                'obtained' => 0,
                'total' => $m->default_total,
                'grade' => '',
            ])->values()->all(),
            'assessments' => $this->assessments->map(fn ($a) => [
                'name' => $a->name,
                'obtained' => 0,
                'total' => $a->default_total,
            ])->values()->all(),
        ];
    }
}
