<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropzoneUpload from '@/Components/Admin/DropzoneUpload.vue';
import DateField from '@/Components/Admin/DateField.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    AcademicCapIcon,
    ClipboardDocumentCheckIcon,
    MapIcon,
    PlusIcon,
    SparklesIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    certificate: Object,
    defaults: Object,
    courses: {
        type: Array,
        default: () => [],
    },
});

const isEdit = computed(() => !!props.certificate?.id);

const form = useForm({
    certificate_code: props.defaults.certificate_code || '',
    student_name: props.defaults.student_name || '',
    course_id: props.defaults.course_id || null,
    course_name: props.defaults.course_name || '',
    batch: props.defaults.batch || '',
    duration: props.defaults.duration || '',
    enrollment_date: props.defaults.enrollment_date || '',
    completion_date: props.defaults.completion_date || '',
    issue_date: props.defaults.issue_date || '',
    signature_name: props.defaults.signature_name || '',
    signature_image: null,
    logo: null,
    certificate_file: null,
    overall_score: props.defaults.overall_score,
    grade: props.defaults.grade || '',
    attendance: props.defaults.attendance ?? 100,
    status: props.defaults.status || 'Completed',
    verify_url_display: props.defaults.verify_url_display || '',
    is_published: props.defaults.is_published ?? true,
    subjects: props.defaults.subjects?.length
        ? props.defaults.subjects
        : [{ name: '', obtained: 0, total: 100, grade: '' }],
    assessments: props.defaults.assessments?.length
        ? props.defaults.assessments
        : [{ name: '', obtained: 0, total: 100 }],
    milestones: props.defaults.milestones?.length
        ? props.defaults.milestones
        : [{ label: '', date_label: '' }],
});

const applyCourse = (courseId) => {
    const selected = props.courses.find((c) => String(c.id) === String(courseId));
    if (!selected) {
        form.course_id = null;
        return;
    }

    form.course_id = selected.id;
    form.course_name = selected.name;
    if (selected.duration) {
        form.duration = selected.duration;
    }

    form.subjects = selected.subjects.length
        ? selected.subjects.map((s) => ({ ...s }))
        : [{ name: '', obtained: 0, total: 100, grade: '' }];

    form.assessments = selected.assessments.length
        ? selected.assessments.map((a) => ({ ...a }))
        : [{ name: '', obtained: 0, total: 100 }];
};

const onCourseChange = (event) => {
    const value = event.target.value;
    if (!value) {
        form.course_id = null;
        return;
    }
    applyCourse(value);
};

const previewSkills = computed(() => {
    const assessments = form.assessments.filter((a) => a.name);
    const assessmentAvg =
        assessments.length === 0
            ? 0
            : assessments.reduce((sum, a) => {
                  const total = Number(a.total) || 100;
                  const pct = total ? (Number(a.obtained) / total) * 100 : 0;
                  return sum + pct;
              }, 0) / assessments.length;

    return form.subjects
        .filter((s) => s.name)
        .map((s) => {
            const total = Number(s.total) || 100;
            const subjectPct = total ? (Number(s.obtained) / total) * 100 : 0;
            return {
                name: s.name,
                percentage: Math.round(subjectPct * 0.7 + assessmentAvg * 0.3),
            };
        });
});

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            is_published: data.is_published ? 1 : 0,
            ...(isEdit.value ? { _method: 'put' } : {}),
        }))
        .post(
            isEdit.value
                ? route('admin.certificates.update', props.certificate.id)
                : route('admin.certificates.store'),
            { forceFormData: true },
        );
};

const addRow = (key, blank) => {
    form[key].push({ ...blank });
};

const removeRow = (key, index) => {
    if (form[key].length === 1) {
        return;
    }
    form[key].splice(index, 1);
};

const fieldClass =
    'mt-1 block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-brand-navy focus:ring-brand-navy/20';
</script>

<template>
    <Head :title="isEdit ? 'Edit Certificate' : 'Generate Certificate'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-lime-dark">
                        Admin Panel
                    </p>
                    <h2 class="text-xl font-bold text-brand-navy">
                        {{ isEdit ? 'Edit Certificate' : 'Generate Certificate' }}
                    </h2>
                </div>
                <Link
                    :href="route('admin.certificates.index')"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm hover:border-brand-navy/30 hover:text-brand-navy"
                >
                    Back to list
                </Link>
            </div>
        </template>

        <div class="py-8">
            <form class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8" @submit.prevent="submit">
                <!-- Certificate details -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-brand-navy to-brand-navy-soft px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Certificate Details</h3>
                        <p class="text-sm text-white/70">
                            Student info, batch, dates, QR verify link &amp; publishing
                        </p>
                    </div>

                    <div class="space-y-6 p-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Student Name</label>
                                <input v-model="form.student_name" type="text" required :class="fieldClass" />
                                <InputError :message="form.errors.student_name" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Certificate ID</label>
                                <input v-model="form.certificate_code" type="text" required :class="fieldClass" />
                                <InputError :message="form.errors.certificate_code" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">
                                    Course Name
                                </label>
                                <select
                                    :value="form.course_id || ''"
                                    required
                                    class="mt-1 block w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-brand-navy focus:ring-brand-navy/20"
                                    @change="onCourseChange"
                                >
                                    <option value="" disabled>Select a course</option>
                                    <option
                                        v-for="course in courses"
                                        :key="course.id"
                                        :value="course.id"
                                    >
                                        {{ course.name }}
                                    </option>
                                </select>
                                <p class="mt-1.5 text-xs text-slate-500">
                                    Selecting a course auto-fills Subject Performance and
                                    Assessment Performance from its modules.
                                </p>
                                <InputError :message="form.errors.course_id || form.errors.course_name" />
                                <p
                                    v-if="!courses.length"
                                    class="mt-2 text-xs font-medium text-amber-600"
                                >
                                    No courses found.
                                    <Link
                                        :href="route('admin.courses.create')"
                                        class="underline"
                                    >
                                        Create a course
                                    </Link>
                                    first.
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Batch</label>
                                <input v-model="form.batch" type="text" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Duration</label>
                                <input
                                    v-model="form.duration"
                                    type="text"
                                    placeholder="6 Months"
                                    :class="fieldClass"
                                />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Attendance %</label>
                                <input
                                    v-model="form.attendance"
                                    type="number"
                                    min="0"
                                    max="100"
                                    :class="fieldClass"
                                />
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <DateField v-model="form.enrollment_date" label="Enrollment Date" />
                            <DateField v-model="form.completion_date" label="Completion Date" />
                            <DateField v-model="form.issue_date" label="Issue Date" />
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Status</label>
                                <input v-model="form.status" type="text" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">
                                    Grade
                                    <span class="font-normal text-slate-400">(auto if empty)</span>
                                </label>
                                <input v-model="form.grade" type="text" placeholder="A" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Verify URL Display</label>
                                <input v-model="form.verify_url_display" type="text" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Signature Name</label>
                                <input v-model="form.signature_name" type="text" :class="fieldClass" />
                            </div>
                        </div>

                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-brand-lime/50 hover:bg-brand-lime-muted/30"
                        >
                            <input
                                v-model="form.is_published"
                                type="checkbox"
                                class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy"
                            />
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Published</span>
                                <span class="block text-xs text-slate-500">
                                    Visible on the public verification page
                                </span>
                            </span>
                        </label>
                    </div>
                </section>

                <!-- Media uploads -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-brand-navy">Media &amp; Action File</h3>
                        <p class="text-sm text-slate-500">
                            Drag &amp; drop logo, signature, and certificate PDF/image
                        </p>
                    </div>
                    <div class="grid gap-5 p-6 lg:grid-cols-3">
                        <DropzoneUpload
                            v-model="form.logo"
                            label="Logo"
                            hint="PNG, JPG up to 2MB"
                            accept="image/*"
                            :existing-url="defaults.logo_url"
                            existing-label="Current logo"
                        />
                        <DropzoneUpload
                            v-model="form.signature_image"
                            label="Signature Image"
                            hint="PNG, JPG up to 2MB"
                            accept="image/*"
                            :existing-url="defaults.signature_image_url"
                            existing-label="Current signature"
                        />
                        <DropzoneUpload
                            v-model="form.certificate_file"
                            label="Certificate File"
                            hint="PDF or image up to 10MB"
                            accept=".pdf,image/*"
                            :existing-url="defaults.certificate_file_url"
                            existing-label="Current certificate file"
                        />
                    </div>
                </section>

                <!-- Subjects -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-navy/5 text-brand-navy"
                            >
                                <AcademicCapIcon class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Subject Performance</h3>
                                <p class="text-sm text-slate-500">
                                    Auto-filled from course modules — enter obtained marks
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-lime px-3.5 py-2 text-sm font-bold text-brand-navy shadow-sm hover:bg-brand-lime-soft"
                            @click="addRow('subjects', { name: '', obtained: 0, total: 100, grade: '' })"
                        >
                            <PlusIcon class="h-4 w-4" />
                            Add Subject
                        </button>
                    </div>

                    <div class="space-y-3 p-6">
                        <div
                            v-for="(row, index) in form.subjects"
                            :key="`subject-${index}`"
                            class="grid gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12"
                        >
                            <input
                                v-model="row.name"
                                type="text"
                                placeholder="Subject / Module"
                                class="rounded-xl border-slate-200 md:col-span-5"
                            />
                            <input
                                v-model="row.obtained"
                                type="number"
                                placeholder="Obtained"
                                class="rounded-xl border-slate-200 md:col-span-2"
                            />
                            <input
                                v-model="row.total"
                                type="number"
                                placeholder="Total"
                                class="rounded-xl border-slate-200 md:col-span-2"
                            />
                            <input
                                v-model="row.grade"
                                type="text"
                                placeholder="Grade"
                                class="rounded-xl border-slate-200 md:col-span-2"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-100 bg-white text-red-500 hover:bg-red-50 md:col-span-1"
                                @click="removeRow('subjects', index)"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Assessments -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-lime-muted text-brand-lime-dark"
                            >
                                <ClipboardDocumentCheckIcon class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Assessment Performance</h3>
                                <p class="text-sm text-slate-500">
                                    Auto-filled from course assessments — enter scores
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-lime px-3.5 py-2 text-sm font-bold text-brand-navy shadow-sm hover:bg-brand-lime-soft"
                            @click="addRow('assessments', { name: '', obtained: 0, total: 100 })"
                        >
                            <PlusIcon class="h-4 w-4" />
                            Add Assessment
                        </button>
                    </div>

                    <div class="space-y-3 p-6">
                        <div
                            v-for="(row, index) in form.assessments"
                            :key="`assessment-${index}`"
                            class="grid gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12"
                        >
                            <input
                                v-model="row.name"
                                type="text"
                                placeholder="Assessment name"
                                class="rounded-xl border-slate-200 md:col-span-6"
                            />
                            <input
                                v-model="row.obtained"
                                type="number"
                                placeholder="Score"
                                class="rounded-xl border-slate-200 md:col-span-2"
                            />
                            <input
                                v-model="row.total"
                                type="number"
                                placeholder="Total"
                                class="rounded-xl border-slate-200 md:col-span-3"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-100 bg-white text-red-500 hover:bg-red-50 md:col-span-1"
                                @click="removeRow('assessments', index)"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Skills preview -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600"
                        >
                            <SparklesIcon class="h-5 w-5" />
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-brand-navy">Skill Proficiency (Auto)</h3>
                            <p class="text-sm text-slate-500">
                                Subject 70% + Assessment average 30%
                            </p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="previewSkills.length" class="space-y-3">
                            <div
                                v-for="skill in previewSkills"
                                :key="skill.name"
                                class="rounded-xl bg-slate-50 px-4 py-3"
                            >
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="font-semibold text-slate-700">{{ skill.name }}</span>
                                    <span class="font-bold text-brand-lime-dark">
                                        {{ skill.percentage }}%
                                    </span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-brand-navy to-brand-lime"
                                        :style="{ width: `${skill.percentage}%` }"
                                    />
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-sm text-slate-400">Add subjects to preview skills.</p>
                    </div>
                </section>

                <!-- Course journey -->
                <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600"
                            >
                                <MapIcon class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Course Journey</h3>
                                <p class="text-sm text-slate-500">Timeline milestones</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-lime px-3.5 py-2 text-sm font-bold text-brand-navy shadow-sm hover:bg-brand-lime-soft"
                            @click="addRow('milestones', { label: '', date_label: '' })"
                        >
                            <PlusIcon class="h-4 w-4" />
                            Add Milestone
                        </button>
                    </div>

                    <div class="space-y-3 p-6">
                        <div
                            v-for="(row, index) in form.milestones"
                            :key="`milestone-${index}`"
                            class="grid gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12"
                        >
                            <input
                                v-model="row.label"
                                type="text"
                                placeholder="Label"
                                class="rounded-xl border-slate-200 md:col-span-5"
                            />
                            <input
                                v-model="row.date_label"
                                type="text"
                                placeholder="Date label (e.g. 10 Nov 2025)"
                                class="rounded-xl border-slate-200 md:col-span-6"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-100 bg-white text-red-500 hover:bg-red-50 md:col-span-1"
                                @click="removeRow('milestones', index)"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </section>

                <div
                    class="sticky bottom-4 z-20 flex flex-wrap items-center justify-end gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur"
                >
                    <Link
                        :href="route('admin.certificates.index')"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="rounded-xl bg-brand-navy px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-navy-deep disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? 'Saving...'
                                : isEdit
                                  ? 'Update Certificate'
                                  : 'Generate Certificate'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
