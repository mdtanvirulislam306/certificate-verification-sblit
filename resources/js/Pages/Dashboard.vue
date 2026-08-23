<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-brand-navy">
                        Certificate Verification Admin
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Generate certificates with subject scores, assessments, course
                        journey, logo, signature, and action files. Skill proficiency is
                        calculated automatically from subject and assessment data.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <Link
                            :href="route('admin.courses.create')"
                            class="rounded-lg border border-brand-navy px-4 py-2.5 text-sm font-semibold text-brand-navy"
                        >
                            Create Course
                        </Link>
                        <Link
                            :href="route('admin.certificates.create')"
                            class="rounded-lg bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white"
                        >
                            Generate Certificate
                        </Link>
                        <Link
                            :href="route('admin.certificates.index')"
                            class="rounded-lg border border-brand-navy px-4 py-2.5 text-sm font-semibold text-brand-navy"
                        >
                            Manage Certificates
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
