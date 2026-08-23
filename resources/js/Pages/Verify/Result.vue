<script setup>
import { Head } from '@inertiajs/vue3';
import {
    ChartBarIcon,
    ShieldCheckIcon,
    StarIcon,
    UserIcon,
} from '@heroicons/vue/24/outline';
import HeroCard from '@/Components/Verify/HeroCard.vue';
import MetricCard from '@/Components/Verify/MetricCard.vue';
import PerformanceTable from '@/Components/Verify/PerformanceTable.vue';
import SkillProficiency from '@/Components/Verify/SkillProficiency.vue';
import CourseJourney from '@/Components/Verify/CourseJourney.vue';
import CertificateInfo from '@/Components/Verify/CertificateInfo.vue';
import ActionPanel from '@/Components/Verify/ActionPanel.vue';
import AuthBanner from '@/Components/Verify/AuthBanner.vue';

defineProps({
    certificate: { type: Object, required: true },
    metrics: { type: Object, required: true },
    subjects: { type: Array, required: true },
    assessments: { type: Array, required: true },
    skills: { type: Array, required: true },
    milestones: { type: Array, required: true },
});

const subjectColumns = [
    { key: 'name', label: 'Subject Name' },
    { key: 'obtained', label: 'Obtained' },
    { key: 'total', label: 'Total' },
    { key: 'percentage', label: 'Percentage' },
    { key: 'grade', label: 'Grade', align: 'right' },
];

const assessmentColumns = [
    { key: 'name', label: 'Assessment' },
    { key: 'obtained', label: 'Score' },
    { key: 'total', label: 'Total' },
    { key: 'percentage', label: 'Percentage' },
];
</script>

<template>
    <div class="min-h-screen bg-brand-muted font-sans text-slate-800">
        <Head :title="`${certificate.studentName} — Certificate Verified`" />

        <main class="container-fluid space-y-6 py-6 sm:py-8">
            <HeroCard :certificate="certificate" />

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <MetricCard
                    title="Overall Score"
                    :value="`${metrics.overallScore}%`"
                    icon-bg="bg-indigo-50 text-indigo-700"
                    trend
                >
                    <template #icon>
                        <ChartBarIcon class="h-7 w-7" stroke-width="1.8" />
                    </template>
                </MetricCard>

                <MetricCard
                    title="Grade"
                    :value="metrics.grade"
                    icon-bg="bg-orange-50 text-orange-500"
                >
                    <template #icon>
                        <StarIcon class="h-7 w-7" stroke-width="1.8" />
                    </template>
                </MetricCard>

                <MetricCard
                    title="Attendance"
                    :value="`${metrics.attendance}%`"
                    icon-bg="bg-sky-50 text-sky-600"
                >
                    <template #icon>
                        <UserIcon class="h-7 w-7" stroke-width="1.8" />
                    </template>
                </MetricCard>

                <MetricCard
                    title="Status"
                    :value="metrics.status"
                    value-class="text-emerald-700"
                    icon-bg="bg-emerald-50 text-emerald-600"
                >
                    <template #icon>
                        <ShieldCheckIcon class="h-7 w-7" stroke-width="1.8" />
                    </template>
                </MetricCard>
            </div>

            <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h3 class="text-base font-bold text-brand-navy">
                        Overall Academic Performance
                    </h3>
                    <span class="text-lg font-extrabold text-brand-lime-dark">
                        {{ metrics.overallScore }}%
                    </span>
                </div>
                <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-gradient-to-r from-brand-lime-dark to-brand-lime"
                        :style="{ width: `${metrics.overallScore}%` }"
                    />
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <PerformanceTable
                    title="Subject Performance"
                    :columns="subjectColumns"
                    :rows="subjects"
                    bar-color="bg-brand-lime"
                />
                <PerformanceTable
                    title="Assessment Performance"
                    :columns="assessmentColumns"
                    :rows="assessments"
                    bar-color="bg-brand-navy"
                />
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <SkillProficiency :skills="skills" />
                <CourseJourney
                    :milestones="milestones"
                    :student-name="certificate.studentName"
                    :course-name="certificate.courseName"
                />
            </div>

            <div class="grid gap-6 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    <CertificateInfo :info="certificate" />
                </div>
                <div class="lg:col-span-2">
                    <ActionPanel
                        :certificate-file="certificate.certificateFile"
                        :verify-url="certificate.verifyUrl"
                    />
                </div>
            </div>

            <AuthBanner
                :verify-url="certificate.verifyUrlDisplay"
                :logo="certificate.logo"
            />
        </main>
    </div>
</template>
