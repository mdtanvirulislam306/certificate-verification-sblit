<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    CheckBadgeIcon,
    MagnifyingGlassIcon,
    ShieldCheckIcon,
} from '@heroicons/vue/24/solid';

const code = ref('');
const error = ref('');
const submitting = ref(false);

const trimmedCode = computed(() => code.value.trim());

const verify = () => {
    error.value = '';

    if (!trimmedCode.value) {
        error.value = 'Please enter a certificate code.';
        return;
    }

    submitting.value = true;
    router.visit(route('verify.show', trimmedCode.value), {
        onFinish: () => {
            submitting.value = false;
        },
        onError: () => {
            error.value = 'Unable to verify this certificate. Please check the code and try again.';
            submitting.value = false;
        },
    });
};
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-brand-muted font-sans text-slate-800">
        <Head title="Verify Certificate — Skill Builders IT Institute" />

        <!-- Atmosphere -->
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div
                class="absolute -left-24 -top-24 h-[28rem] w-[28rem] rounded-full bg-brand-lime/15 blur-3xl"
            />
            <div
                class="absolute -right-20 top-1/3 h-[22rem] w-[22rem] rounded-full bg-brand-navy/10 blur-3xl"
            />
            <div
                class="absolute bottom-0 left-1/2 h-64 w-[40rem] -translate-x-1/2 rounded-full bg-brand-lime/10 blur-3xl"
            />
        </div>


        <main class="relative z-10">
            <section class="container-fluid py-12 sm:py-16 lg:py-20">
                <div class="mx-auto max-w-3xl text-center">
                    <div
                        class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-card ring-1 ring-brand-border sm:h-20 sm:w-20"
                    >
                        <img
                            src="/images/logo.jpg"
                            alt=""
                            class="h-12 w-12 object-contain sm:h-14 sm:w-14"
                        />
                    </div>

                    <p
                        class="inline-flex items-center gap-1.5 rounded-full bg-brand-lime-muted px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-brand-lime-dark ring-1 ring-brand-lime/40"
                    >
                        <ShieldCheckIcon class="h-3.5 w-3.5" />
                        Official Verification Portal
                    </p>

                    <h1
                        class="mt-4 text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl lg:text-5xl"
                    >
                        Skill Builders IT Institute
                    </h1>
                    <p class="mx-auto mt-3 max-w-xl text-base text-slate-600 sm:text-lg">
                        Verify the authenticity of certificates issued by Skill Builders
                        using the unique certificate code.
                    </p>
                </div>

                <div class="mx-auto mt-10 max-w-xl">
                    <form
                        class="rounded-2xl bg-white p-5 shadow-card ring-1 ring-brand-border sm:p-7"
                        @submit.prevent="verify"
                    >
                        <label
                            for="certificate-code"
                            class="block text-left text-sm font-semibold text-brand-navy"
                        >
                            Certificate Code
                        </label>
                        <p class="mt-1 text-left text-sm text-slate-500">
                            Enter the code printed on the certificate or shown with the QR.
                        </p>

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                            <div class="relative flex-1">
                                <MagnifyingGlassIcon
                                    class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                />
                                <input
                                    id="certificate-code"
                                    v-model="code"
                                    type="text"
                                    autocomplete="off"
                                    spellcheck="false"
                                    placeholder="e.g. SBLIT-2024-00123"
                                    class="w-full rounded-xl border-brand-border bg-brand-muted/60 py-3.5 pl-11 pr-4 text-sm font-medium text-brand-navy placeholder:text-slate-400 focus:border-brand-lime focus:bg-white focus:ring-brand-lime"
                                    :aria-invalid="!!error"
                                    @input="error = ''"
                                />
                            </div>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-navy px-6 py-3.5 text-sm font-bold text-white transition hover:bg-brand-navy-soft disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="submitting"
                            >
                                <CheckBadgeIcon class="h-5 w-5 text-brand-lime" />
                                {{ submitting ? 'Verifying…' : 'Verify' }}
                            </button>
                        </div>

                        <p v-if="error" class="mt-3 text-left text-sm font-medium text-red-600">
                            {{ error }}
                        </p>
                    </form>

                    <div
                        class="mt-6 grid gap-3 text-left sm:grid-cols-3"
                    >
                        <div
                            class="rounded-xl bg-white/80 px-4 py-3 ring-1 ring-brand-border"
                        >
                            <p class="text-xs font-bold uppercase tracking-wide text-brand-lime-dark">
                                Authentic
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                Issued by authorized institute
                            </p>
                        </div>
                        <div
                            class="rounded-xl bg-white/80 px-4 py-3 ring-1 ring-brand-border"
                        >
                            <p class="text-xs font-bold uppercase tracking-wide text-brand-lime-dark">
                                Instant
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                Results in seconds
                            </p>
                        </div>
                        <div
                            class="rounded-xl bg-white/80 px-4 py-3 ring-1 ring-brand-border"
                        >
                            <p class="text-xs font-bold uppercase tracking-wide text-brand-lime-dark">
                                Secure
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                Tamper-proof records
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 border-t border-brand-border/60 py-6">
            <div class="container-fluid text-center text-sm text-slate-500">
                © {{ new Date().getFullYear() }} Skill Builders IT Institute. All rights reserved.
            </div>
        </footer>
    </div>
</template>
