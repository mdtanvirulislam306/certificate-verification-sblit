<?php

namespace App\Services;

use App\Models\Certificate;

class CertificateMetricsService
{
    public function percentage(int $obtained, int $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($obtained / $total) * 100);
    }

    public function gradeFromPercentage(int $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }

    /**
     * Skill proficiency = weighted blend of subject % and overall assessment %.
     * Each subject module becomes one skill.
     */
    public function buildSkillsFromPerformance(array $subjects, array $assessments): array
    {
        $assessmentAvg = $this->averagePercentage($assessments);

        $skills = [];
        foreach (array_values($subjects) as $index => $subject) {
            if (blank($subject['name'] ?? null)) {
                continue;
            }

            $subjectPct = (int) ($subject['percentage'] ?? $this->percentage(
                (int) ($subject['obtained'] ?? 0),
                (int) ($subject['total'] ?? 100),
            ));

            $skills[] = [
                'name' => $subject['name'],
                'percentage' => (int) round(($subjectPct * 0.7) + ($assessmentAvg * 0.3)),
                'sort_order' => $index,
            ];
        }

        return $skills;
    }

    public function averagePercentage(array $rows): float
    {
        $values = [];
        foreach ($rows as $row) {
            if (blank($row['name'] ?? null)) {
                continue;
            }

            $values[] = (int) ($row['percentage'] ?? $this->percentage(
                (int) ($row['obtained'] ?? 0),
                (int) ($row['total'] ?? 100),
            ));
        }

        if ($values === []) {
            return 0;
        }

        return array_sum($values) / count($values);
    }

    public function computeOverallScore(array $subjects, array $assessments): float
    {
        $subjectAvg = $this->averagePercentage($subjects);
        $assessmentAvg = $this->averagePercentage($assessments);

        if ($subjectAvg === 0.0 && $assessmentAvg === 0.0) {
            return 0;
        }

        if ($subjectAvg === 0.0) {
            return round($assessmentAvg, 1);
        }

        if ($assessmentAvg === 0.0) {
            return round($subjectAvg, 1);
        }

        return round(($subjectAvg * 0.6) + ($assessmentAvg * 0.4), 1);
    }

    public function syncRelated(Certificate $certificate, array $subjects, array $assessments, array $milestones): void
    {
        $certificate->subjects()->delete();
        $certificate->assessments()->delete();
        $certificate->skills()->delete();
        $certificate->milestones()->delete();

        $normalizedSubjects = [];
        foreach (array_values($subjects) as $index => $subject) {
            if (blank($subject['name'] ?? null)) {
                continue;
            }

            $obtained = (int) ($subject['obtained'] ?? 0);
            $total = max(1, (int) ($subject['total'] ?? 100));
            $percentage = $this->percentage($obtained, $total);
            $grade = $subject['grade'] ?? $this->gradeFromPercentage($percentage);

            $normalizedSubjects[] = [
                'name' => $subject['name'],
                'obtained' => $obtained,
                'total' => $total,
                'percentage' => $percentage,
                'grade' => $grade,
                'sort_order' => $index,
            ];
        }

        $normalizedAssessments = [];
        foreach (array_values($assessments) as $index => $assessment) {
            if (blank($assessment['name'] ?? null)) {
                continue;
            }

            $obtained = (int) ($assessment['obtained'] ?? 0);
            $total = max(1, (int) ($assessment['total'] ?? 100));
            $percentage = $this->percentage($obtained, $total);

            $normalizedAssessments[] = [
                'name' => $assessment['name'],
                'obtained' => $obtained,
                'total' => $total,
                'percentage' => $percentage,
                'sort_order' => $index,
            ];
        }

        $certificate->subjects()->createMany($normalizedSubjects);
        $certificate->assessments()->createMany($normalizedAssessments);
        $certificate->skills()->createMany(
            $this->buildSkillsFromPerformance($normalizedSubjects, $normalizedAssessments)
        );

        $milestoneRows = [];
        foreach (array_values($milestones) as $index => $milestone) {
            if (blank($milestone['label'] ?? null)) {
                continue;
            }

            $milestoneRows[] = [
                'label' => $milestone['label'],
                'date_label' => $milestone['date_label'] ?? $milestone['date'] ?? null,
                'sort_order' => $index,
            ];
        }
        $certificate->milestones()->createMany($milestoneRows);

        $overall = $this->computeOverallScore($normalizedSubjects, $normalizedAssessments);

        $certificate->update([
            'overall_score' => $overall,
            'grade' => $certificate->grade ?: $this->gradeFromPercentage((int) round($overall)),
        ]);
    }
}
