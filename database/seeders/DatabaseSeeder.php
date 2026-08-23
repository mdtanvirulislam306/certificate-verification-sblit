<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Services\CertificateMetricsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@skillbuilders.edu.bd'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $course = Course::query()->updateOrCreate(
            ['name' => 'Professional Digital Marketing'],
            [
                'duration' => '6 Months',
                'is_active' => true,
            ]
        );

        $course->modules()->delete();
        $course->assessments()->delete();

        $modules = [
            'Digital Marketing Fundamentals',
            'Facebook Marketing',
            'Google Ads & Analytics',
            'SEO & Content Strategy',
            'Email Marketing',
            'Social Media Strategy',
            'Final Capstone Project',
        ];

        foreach ($modules as $index => $name) {
            $course->modules()->create([
                'name' => $name,
                'default_total' => 100,
                'sort_order' => $index,
            ]);
        }

        $assessments = [
            ['name' => 'Assignment 01', 'default_total' => 20],
            ['name' => 'Assignment 02', 'default_total' => 20],
            ['name' => 'Weekly Quiz', 'default_total' => 50],
            ['name' => 'Mid-Term Exam', 'default_total' => 100],
            ['name' => 'Practical Project', 'default_total' => 100],
            ['name' => 'Final Assessment', 'default_total' => 100],
        ];

        foreach ($assessments as $index => $assessment) {
            $course->assessments()->create([
                ...$assessment,
                'sort_order' => $index,
            ]);
        }

        $certificate = Certificate::query()->updateOrCreate(
            ['certificate_code' => 'SBLIT-DM-2026-00125'],
            [
                'student_name' => 'Mahazabin Moumita',
                'course_id' => $course->id,
                'course_name' => $course->name,
                'batch' => 'DM-12',
                'duration' => '6 Months',
                'enrollment_date' => '2025-11-10',
                'completion_date' => '2026-05-15',
                'issue_date' => '2026-05-20',
                'signature_name' => 'Skill Builders',
                'attendance' => 94,
                'status' => 'Completed',
                'verify_url_display' => 'skillbuilders.edu.bd/verify',
                'is_published' => true,
                'grade' => 'A',
            ]
        );

        app(CertificateMetricsService::class)->syncRelated(
            $certificate,
            [
                ['name' => 'Digital Marketing Fundamentals', 'obtained' => 88, 'total' => 100],
                ['name' => 'Facebook Marketing', 'obtained' => 92, 'total' => 100],
                ['name' => 'Google Ads & Analytics', 'obtained' => 85, 'total' => 100],
                ['name' => 'SEO & Content Strategy', 'obtained' => 90, 'total' => 100],
                ['name' => 'Email Marketing', 'obtained' => 82, 'total' => 100],
                ['name' => 'Social Media Strategy', 'obtained' => 86, 'total' => 100],
                ['name' => 'Final Capstone Project', 'obtained' => 89, 'total' => 100],
            ],
            [
                ['name' => 'Assignment 01', 'obtained' => 18, 'total' => 20],
                ['name' => 'Assignment 02', 'obtained' => 17, 'total' => 20],
                ['name' => 'Weekly Quiz', 'obtained' => 42, 'total' => 50],
                ['name' => 'Mid-Term Exam', 'obtained' => 78, 'total' => 100],
                ['name' => 'Practical Project', 'obtained' => 91, 'total' => 100],
                ['name' => 'Final Assessment', 'obtained' => 88, 'total' => 100],
            ],
            [
                ['label' => 'Enrollment', 'date_label' => '10 Nov 2025'],
                ['label' => 'Foundation', 'date_label' => 'Dec 2025'],
                ['label' => 'Advanced Training', 'date_label' => 'Feb 2026'],
                ['label' => 'Assessments', 'date_label' => 'Mar 2026'],
                ['label' => 'Final Project', 'date_label' => 'Apr 2026'],
                ['label' => 'Certificate Issued', 'date_label' => '20 May 2026'],
            ],
        );
    }
}
