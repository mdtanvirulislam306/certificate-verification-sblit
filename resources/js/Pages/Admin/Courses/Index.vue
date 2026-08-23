<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    courses: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

watch(search, (value) => {
    router.get(
        route('admin.courses.index'),
        { search: value || undefined },
        { preserveState: true, replace: true },
    );
});

const destroy = (id) => {
    if (confirm('Delete this course?')) {
        router.delete(route('admin.courses.destroy', id));
    }
};
</script>

<template>
    <Head title="Courses" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-lime-dark">
                        Admin Panel
                    </p>
                    <h2 class="text-xl font-bold text-brand-navy">Courses</h2>
                </div>
                <Link
                    :href="route('admin.courses.create')"
                    class="rounded-xl bg-brand-navy px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy-deep"
                >
                    Create Course
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div
                    v-if="$page.props.flash?.success"
                    class="rounded-xl bg-brand-lime-muted px-4 py-3 text-sm font-medium text-brand-navy"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-soft">
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search courses..."
                        class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-navy focus:ring-brand-navy/20"
                    />
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Course Name</th>
                                <th class="px-4 py-3">Duration</th>
                                <th class="px-4 py-3">Modules</th>
                                <th class="px-4 py-3">Assessments</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="item in courses.data"
                                :key="item.id"
                                class="hover:bg-slate-50"
                            >
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ item.name }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ item.duration || '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ item.modules_count }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ item.assessments_count }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                        :class="
                                            item.is_active
                                                ? 'bg-brand-lime-muted text-brand-lime-dark'
                                                : 'bg-slate-100 text-slate-500'
                                        "
                                    >
                                        {{ item.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="space-x-3 px-4 py-3 text-right">
                                    <Link
                                        :href="route('admin.courses.edit', item.id)"
                                        class="font-medium text-brand-navy hover:underline"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        type="button"
                                        class="font-medium text-red-600 hover:underline"
                                        @click="destroy(item.id)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!courses.data.length">
                                <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                                    No courses yet. Create one to use in certificate generation.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
