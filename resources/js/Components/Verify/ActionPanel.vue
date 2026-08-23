<script setup>
import {
    ArrowDownTrayIcon,
    DocumentMagnifyingGlassIcon,
    ShareIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    certificateFile: { type: String, default: null },
    verifyUrl: { type: String, required: true },
});

const share = async () => {
    try {
        if (navigator.share) {
            await navigator.share({
                title: 'Certificate Verification',
                url: props.verifyUrl,
            });
            return;
        }
        await navigator.clipboard.writeText(props.verifyUrl);
        alert('Verification link copied.');
    } catch {
        // user cancelled
    }
};
</script>

<template>
    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
        <h3 class="text-base font-bold text-brand-navy">Actions</h3>

        <div class="mt-5 flex flex-col gap-3">
            <a
                v-if="certificateFile"
                :href="certificateFile"
                target="_blank"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-navy px-4 py-3.5 text-sm font-bold text-white transition hover:bg-brand-navy-deep"
            >
                <DocumentMagnifyingGlassIcon class="h-5 w-5" />
                View Original Certificate
            </a>
            <button
                v-else
                type="button"
                disabled
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-navy/50 px-4 py-3.5 text-sm font-bold text-white"
            >
                <DocumentMagnifyingGlassIcon class="h-5 w-5" />
                View Original Certificate
            </button>

            <a
                v-if="certificateFile"
                :href="certificateFile"
                download
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-brand-navy bg-white px-4 py-3.5 text-sm font-bold text-brand-navy transition hover:border-brand-lime hover:bg-brand-lime-muted"
            >
                <ArrowDownTrayIcon class="h-5 w-5" />
                Download Certificate (PDF)
            </a>
            <button
                v-else
                type="button"
                disabled
                class="inline-flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 text-sm font-bold text-slate-400"
            >
                <ArrowDownTrayIcon class="h-5 w-5" />
                Download Certificate (PDF)
            </button>

            <button
                type="button"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-brand-navy bg-white px-4 py-3.5 text-sm font-bold text-brand-navy transition hover:border-brand-lime hover:bg-brand-lime-muted"
                @click="share"
            >
                <ShareIcon class="h-5 w-5" />
                Share Verification Link
            </button>
        </div>
    </div>
</template>
