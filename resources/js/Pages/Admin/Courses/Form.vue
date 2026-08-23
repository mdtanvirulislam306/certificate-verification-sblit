<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    AcademicCapIcon,
    ClipboardDocumentCheckIcon,
    PlusIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    course: Object,
    defaults: Object,
});

const isEdit = computed(() => !!props.course?.id);

const form = useForm({
    name: props.defaults.name || '',
    duration: props.defaults.duration || '',
    is_active: props.defaults.is_active ?? true,
    modules: props.defaults.modules?.length
        ? props.defaults.modules
        : [{ name: '', default_total: 100 }],
    assessments: props.defaults.assessments?.length
        ? props.defaults.assessments
        : [{ name: '', default_total: 100 }],
});

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            is_active: data.is_active ? 1 : 0,
            ...(isEdit.value ? { _method: 'put' } : {}),
        }))
        .post(
            isEdit.value
                ? route('admin.courses.update', props.course.id)
                : route('admin.courses.store'),
        );
};

const addRow = (key, blank) => form[key].push({ ...blank });
const removeRow = (key, index) => {
    if (form[key].length > 1) {
        form[key].splice(index, 1);
    }
};

const fieldClass =
    'mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-brand-navy focus:ring-brand-navy/20';
</script>

<template>
    <Head :title="isEdit ? 'Edit Course' : 'Create Course'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-lime-dark">
                        Admin Panel
                    </p>
                    <h2 class="text-xl font-bold text-brand-navy">
                        {{ isEdit ? 'Edit Course' : 'Create Course' }}
                    </h2>
                </div>
                <Link
                    :href="route('admin.courses.index')"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm"
                >
                    Back to courses
                </Link>
            </div>
        </template>

        <div class="py-8">
            <form class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8" @submit.prevent="submit">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
                    <div class="border-b border-slate-100 bg-gradient-to-r from-brand-navy to-brand-navy-soft px-6 py-4">
                        <h3 class="text-lg font-bold text-white">Course Info</h3>
                        <p class="text-sm text-white/70">
                            Course name and modules will auto-fill certificate form fields
                        </p>
                    </div>
                    <div class="grid gap-4 p-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Course Name</label>
                            <input v-model="form.name" type="text" required :class="fieldClass" />
                            <InputError :message="form.errors.name" />
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
                        <label
                            class="flex cursor-pointer items-center gap-3 self-end rounded-xl border border-slate-200 bg-slate-50 px-4 py-3"
                        >
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="rounded border-slate-300 text-brand-navy focus:ring-brand-navy"
                            />
                            <span class="text-sm font-semibold text-slate-700">Active</span>
                        </label>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-navy/5 text-brand-navy"
                            >
                                <AcademicCapIcon class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Modules</h3>
                                <p class="text-sm text-slate-500">
                                    Become Subject Performance rows
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-lime px-3.5 py-2 text-sm font-bold text-brand-navy"
                            @click="addRow('modules', { name: '', default_total: 100 })"
                        >
                            <PlusIcon class="h-4 w-4" />
                            Add Module
                        </button>
                    </div>
                    <div class="space-y-3 p-6">
                        <div
                            v-for="(row, index) in form.modules"
                            :key="`module-${index}`"
                            class="grid gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 md:grid-cols-12"
                        >
                            <input
                                v-model="row.name"
                                type="text"
                                placeholder="Module name"
                                class="rounded-xl border-slate-200 md:col-span-8"
                            />
                            <input
                                v-model="row.default_total"
                                type="number"
                                placeholder="Total marks"
                                class="rounded-xl border-slate-200 md:col-span-3"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-100 bg-white text-red-500 md:col-span-1"
                                @click="removeRow('modules', index)"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-lime-muted text-brand-lime-dark"
                            >
                                <ClipboardDocumentCheckIcon class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Assessments</h3>
                                <p class="text-sm text-slate-500">
                                    Become Assessment Performance rows
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-lime px-3.5 py-2 text-sm font-bold text-brand-navy"
                            @click="addRow('assessments', { name: '', default_total: 100 })"
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
                                class="rounded-xl border-slate-200 md:col-span-8"
                            />
                            <input
                                v-model="row.default_total"
                                type="number"
                                placeholder="Total marks"
                                class="rounded-xl border-slate-200 md:col-span-3"
                            />
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-red-100 bg-white text-red-500 md:col-span-1"
                                @click="removeRow('assessments', index)"
                            >
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-3">
                    <Link
                        :href="route('admin.courses.index')"
                        class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="rounded-xl bg-brand-navy px-6 py-2.5 text-sm font-bold text-white disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ isEdit ? 'Update Course' : 'Create Course' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
