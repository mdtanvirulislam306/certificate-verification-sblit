<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CertificateSheet from '@/Components/Admin/CertificateSheet.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    ArrowDownTrayIcon,
    DocumentArrowDownIcon,
    PhotoIcon,
} from '@heroicons/vue/24/outline';
import {
    downloadCertificateImage,
    downloadCertificatePdf,
} from '@/Utils/downloadCertificate';

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    },
});

const sheetRef = ref(null);
const busy = ref(null);

const runDownload = async (type) => {
    if (!sheetRef.value || busy.value) {
        return;
    }

    busy.value = type;
    try {
        const el = sheetRef.value;
        const code = props.certificate.id;
        if (type === 'image') {
            await downloadCertificateImage(el, `${code}-certificate.png`);
        } else {
            await downloadCertificatePdf(el, `${code}-certificate.pdf`);
        }
    } catch (error) {
        console.error(error);
        alert('Download failed. Please try again.');
    } finally {
        busy.value = null;
    }
};
</script>

<template>
    <Head :title="`Generate Certificate — ${certificate.studentName}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-lime-dark">
                        Certificate Preview
                    </p>
                    <h2 class="text-xl font-bold text-brand-navy">
                        {{ certificate.studentName }}
                    </h2>
                </div>
                <Link
                    :href="route('admin.certificates.index')"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm"
                >
                    Back to list
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-soft"
                >
                    <div>
                        <p class="text-sm font-semibold text-brand-navy">
                            Preview ready
                        </p>
                        <p class="text-xs text-slate-500">
                            Download as high-quality image or PDF
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-brand-navy hover:border-brand-navy disabled:opacity-50"
                            :disabled="!!busy"
                            @click="runDownload('image')"
                        >
                            <PhotoIcon class="h-4 w-4" />
                            {{ busy === 'image' ? 'Preparing...' : 'Download Image' }}
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl bg-brand-navy px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-navy-deep disabled:opacity-50"
                            :disabled="!!busy"
                            @click="runDownload('pdf')"
                        >
                            <DocumentArrowDownIcon class="h-4 w-4" />
                            {{ busy === 'pdf' ? 'Preparing...' : 'Download PDF' }}
                        </button>
                    </div>
                </div>

                <div class="overflow-auto rounded-2xl bg-slate-200/60 p-4 sm:p-8">
                    <div ref="sheetRef">
                        <CertificateSheet :certificate="certificate" />
                    </div>
                </div>

                <p class="flex items-center justify-center gap-2 text-center text-xs text-slate-500">
                    <ArrowDownTrayIcon class="h-4 w-4" />
                    Tip: Use Download PDF for print-ready A4 landscape output.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
