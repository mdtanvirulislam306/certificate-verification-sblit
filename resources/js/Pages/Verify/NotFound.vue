<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ExclamationTriangleIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/solid';

const props = defineProps({
    code: {
        type: String,
        required: true,
    },
    reason: {
        type: String,
        required: true,
        validator: (value) => ['not_found', 'inactive'].includes(value),
    },
});

const retryCode = ref(props.code);
const localError = ref('');
const submitting = ref(false);

const title = computed(() =>
    props.reason === 'inactive'
        ? 'Certificate Not Active'
        : 'Certificate Not Found',
);

const message = computed(() => {
    if (props.reason === 'inactive') {
        return 'This certificate exists in our system but is not currently active for public verification. It may be unpublished or awaiting approval.';
    }

    return 'We could not find any certificate matching this code. Please double-check the code on your certificate and try again.';
});

const retry = () => {
    localError.value = '';
    const next = retryCode.value.trim();

    if (!next) {
        localError.value = 'Please enter a certificate code.';
        return;
    }

    submitting.value = true;
    router.visit(route('verify.show', next), {
        onFinish: () => {
            submitting.value = false;
        },
    });
};
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-brand-muted font-sans text-slate-800">
        <Head :title="title" />

        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div
                class="absolute -left-24 -top-24 h-[28rem] w-[28rem] rounded-full bg-amber-300/20 blur-3xl"
            />
            <div
                class="absolute -right-20 top-1/3 h-[22rem] w-[22rem] rounded-full bg-brand-navy/10 blur-3xl"
            />
        </div>

        <main class="relative z-10 container-fluid flex min-h-screen items-center justify-center py-12">
            <div class="w-full max-w-lg">
                <div
                    class="rounded-2xl bg-white p-6 shadow-card ring-1 ring-brand-border sm:p-8"
                >
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 ring-1 ring-amber-200"
                        >
                            <ExclamationTriangleIcon class="h-8 w-8 text-amber-500" />
                        </div>

                        <img
                            src="/images/logo.jpg"
                            alt="Skill Builders IT Institute"
                            class="mt-5 h-12 w-12 object-contain"
                        />

                        <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-brand-navy">
                            {{ title }}
                        </h1>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">
                            {{ message }}
                        </p>

                        <div
                            class="mt-5 w-full rounded-xl bg-brand-muted/80 px-4 py-3 text-left ring-1 ring-brand-border"
                        >
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                Entered code
                            </p>
                            <p class="mt-1 break-all font-mono text-sm font-semibold text-brand-navy">
                                {{ code }}
                            </p>
                        </div>
                    </div>

                    <form class="mt-6 space-y-3" @submit.prevent="retry">
                        <label
                            for="retry-code"
                            class="block text-sm font-semibold text-brand-navy"
                        >
                            Try another code
                        </label>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <div class="relative flex-1">
                                <MagnifyingGlassIcon
                                    class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                />
                                <input
                                    id="retry-code"
                                    v-model="retryCode"
                                    type="text"
                                    autocomplete="off"
                                    spellcheck="false"
                                    class="w-full rounded-xl border-brand-border bg-brand-muted/60 py-3 pl-11 pr-4 text-sm font-medium text-brand-navy focus:border-brand-lime focus:bg-white focus:ring-brand-lime"
                                    @input="localError = ''"
                                />
                            </div>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-brand-navy px-5 py-3 text-sm font-bold text-white transition hover:bg-brand-navy-soft disabled:opacity-60"
                                :disabled="submitting"
                            >
                                {{ submitting ? 'Checking…' : 'Verify again' }}
                            </button>
                        </div>
                        <p v-if="localError" class="text-sm font-medium text-red-600">
                            {{ localError }}
                        </p>
                    </form>

                    <div class="mt-6 flex flex-col gap-2 border-t border-brand-border pt-5 sm:flex-row sm:justify-between">
                        <Link
                            :href="route('home')"
                            class="text-center text-sm font-semibold text-brand-navy hover:text-brand-lime-dark"
                        >
                            ← Back to home
                        </Link>
                        <p class="text-center text-xs text-slate-500 sm:text-right">
                            Need help? Contact Skill Builders IT Institute.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
