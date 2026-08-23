<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import {
    ClipboardDocumentIcon,
    CheckIcon,
    QrCodeIcon,
    ArrowDownTrayIcon,
} from '@heroicons/vue/24/outline';
import { downloadBrandedQr } from '@/Utils/downloadBrandedQr';

const props = defineProps({
    certificates: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const copiedId = ref(null);
const downloadingId = ref(null);

watch(search, (value) => {
    router.get(
        route('admin.certificates.index'),
        { search: value || undefined },
        { preserveState: true, replace: true },
    );
});

const verifyLink = (code) => route('verify.show', code, true);

const copyLink = async (item) => {
    const link = verifyLink(item.certificate_code);
    try {
        await navigator.clipboard.writeText(link);
        copiedId.value = item.id;
        setTimeout(() => {
            if (copiedId.value === item.id) {
                copiedId.value = null;
            }
        }, 2000);
    } catch {
        prompt('Copy this verification link:', link);
    }
};

const downloadQr = async (item) => {
    downloadingId.value = item.id;
    try {
        await downloadBrandedQr({
            url: verifyLink(item.certificate_code),
            filename: `${item.certificate_code}-qr.png`,
            logoUrl: '/images/logo.jpg',
        });
    } catch (error) {
        console.error(error);
        alert('Failed to generate QR code. Please try again.');
    } finally {
        downloadingId.value = null;
    }
};

const destroy = (id) => {
    if (confirm('Delete this certificate?')) {
        router.delete(route('admin.certificates.destroy', id));
    }
};
</script>

<template>
    <Head title="Certificates" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Certificates
                </h2>
                <Link
                    :href="route('admin.certificates.create')"
                    class="rounded-lg bg-brand-navy px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy-deep"
                >
                    New Certificate
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div
                    v-if="$page.props.flash?.success"
                    class="rounded-lg bg-brand-lime-muted px-4 py-3 text-sm font-medium text-brand-navy"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div class="rounded-xl bg-white p-4 shadow-sm">
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search by name, code, course, batch..."
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-brand-navy focus:ring-brand-navy"
                    />
                </div>

                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="px-4 py-3">Certificate ID</th>
                                <th class="px-4 py-3">Course</th>
                                <th class="px-4 py-3">Score</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="item in certificates.data"
                                :key="item.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ item.student_name }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ item.certificate_code }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ item.course_name }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ item.overall_score }}% / {{ item.grade }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                        :class="
                                            item.is_published
                                                ? 'bg-brand-lime-muted text-brand-lime-dark'
                                                : 'bg-gray-100 text-gray-500'
                                        "
                                    >
                                        {{ item.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.certificates.generate', item.id)"
                                            class="inline-flex items-center gap-1 rounded-lg bg-brand-navy px-2.5 py-1 text-xs font-semibold text-white hover:bg-brand-navy-deep"
                                        >
                                            Generate Certificate
                                        </Link>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:border-brand-navy hover:text-brand-navy"
                                            @click="copyLink(item)"
                                        >
                                            <CheckIcon
                                                v-if="copiedId === item.id"
                                                class="h-3.5 w-3.5 text-brand-lime-dark"
                                            />
                                            <ClipboardDocumentIcon
                                                v-else
                                                class="h-3.5 w-3.5"
                                            />
                                            {{ copiedId === item.id ? 'Copied' : 'Copy Link' }}
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-lg border border-brand-lime/40 bg-brand-lime-muted px-2.5 py-1 text-xs font-semibold text-brand-navy hover:bg-brand-lime"
                                            :disabled="downloadingId === item.id"
                                            @click="downloadQr(item)"
                                        >
                                            <ArrowDownTrayIcon
                                                v-if="downloadingId === item.id"
                                                class="h-3.5 w-3.5 animate-pulse"
                                            />
                                            <QrCodeIcon v-else class="h-3.5 w-3.5" />
                                            {{
                                                downloadingId === item.id
                                                    ? 'Preparing...'
                                                    : 'Download QR'
                                            }}
                                        </button>
                                        <a
                                            :href="verifyLink(item.certificate_code)"
                                            target="_blank"
                                            class="text-brand-navy hover:underline"
                                        >
                                            View
                                        </a>
                                        <Link
                                            :href="route('admin.certificates.edit', item.id)"
                                            class="text-brand-lime-dark hover:underline"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            type="button"
                                            class="text-red-600 hover:underline"
                                            @click="destroy(item.id)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!certificates.data.length">
                                <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                                    No certificates yet. Generate one to get started.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="certificates.links?.length > 3"
                    class="flex flex-wrap gap-2"
                >
                    <Link
                        v-for="link in certificates.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="rounded border px-3 py-1 text-sm"
                        :class="
                            link.active
                                ? 'border-brand-navy bg-brand-navy text-white'
                                : 'border-gray-200 bg-white text-gray-600'
                        "
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
