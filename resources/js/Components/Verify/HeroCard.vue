<script setup>
import { CheckBadgeIcon } from '@heroicons/vue/24/solid';
import {
    DocumentTextIcon,
    UserGroupIcon,
    CalendarDaysIcon,
} from '@heroicons/vue/24/outline';
import QrcodeVue from 'qrcode.vue';

defineProps({
    certificate: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section
        class="hero-card relative overflow-hidden rounded-2xl p-6 text-white shadow-card sm:p-8"
    >
        <!-- Base green gradient -->
        <div class="pointer-events-none absolute inset-0 hero-card__gradient" />

        <!-- Soft glow accents (like design) -->
        <div class="pointer-events-none absolute inset-0 hero-card__glows" />

        <!-- Wavy background pattern from UI/UX -->
        <svg
            class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.55]"
            viewBox="0 0 1200 420"
            preserveAspectRatio="xMidYMid slice"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
            <defs>
                <linearGradient id="waveStroke" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#8DC63F" stop-opacity="0" />
                    <stop offset="20%" stop-color="#8DC63F" stop-opacity="0.45" />
                    <stop offset="50%" stop-color="#A4D65E" stop-opacity="0.65" />
                    <stop offset="80%" stop-color="#8DC63F" stop-opacity="0.4" />
                    <stop offset="100%" stop-color="#8DC63F" stop-opacity="0" />
                </linearGradient>
            </defs>

            <path
                d="M-40 80 C 120 20, 240 140, 400 90 S 680 10, 860 70 S 1100 150, 1240 90"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.6"
            />
            <path
                d="M-40 120 C 140 60, 280 180, 460 120 S 740 40, 920 110 S 1120 190, 1240 130"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.4"
                opacity="0.85"
            />
            <path
                d="M-40 165 C 160 100, 300 220, 480 155 S 760 70, 940 150 S 1140 230, 1240 175"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.3"
                opacity="0.75"
            />
            <path
                d="M-40 210 C 150 145, 320 260, 500 195 S 780 110, 960 190 S 1160 270, 1240 220"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.25"
                opacity="0.7"
            />
            <path
                d="M-40 255 C 170 185, 340 300, 520 235 S 800 150, 980 230 S 1160 310, 1240 265"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.2"
                opacity="0.65"
            />
            <path
                d="M-40 300 C 180 230, 360 340, 540 280 S 820 190, 1000 275 S 1160 350, 1240 310"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.15"
                opacity="0.55"
            />
            <path
                d="M-40 345 C 160 280, 350 390, 560 325 S 840 240, 1020 320 S 1160 390, 1240 355"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1.1"
                opacity="0.45"
            />
            <path
                d="M-40 385 C 180 325, 370 420, 580 365 S 860 290, 1040 360 S 1160 420, 1240 395"
                fill="none"
                stroke="url(#waveStroke)"
                stroke-width="1"
                opacity="0.35"
            />

            <!-- Soft filled wave shapes for depth -->
            <path
                d="M-40 200 C 200 120, 400 280, 650 180 S 1000 80, 1240 160 L 1240 420 L -40 420 Z"
                fill="#8DC63F"
                opacity="0.06"
            />
            <path
                d="M-40 280 C 220 200, 450 360, 720 260 S 1050 160, 1240 250 L 1240 420 L -40 420 Z"
                fill="#A4D65E"
                opacity="0.05"
            />
        </svg>

        <div class="relative grid gap-6 lg:grid-cols-12 lg:items-stretch">
            <div class="flex flex-col lg:col-span-7">
                <div
                    class="inline-flex w-fit items-center gap-1.5 rounded-full bg-brand-lime/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-brand-lime-soft ring-1 ring-brand-lime/50 shadow-[0_0_24px_rgba(141,198,63,0.25)]"
                >
                    <CheckBadgeIcon class="h-4 w-4 text-brand-lime" />
                    Certificate Verified
                </div>

                <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">
                    {{ certificate.studentName }}
                </h1>
                <p class="mt-2 text-base font-medium text-white/85 sm:text-lg">
                    {{ certificate.courseName }}
                </p>

                <div
                    class="mt-6 grid gap-4 border-t border-white/15 pt-5 sm:grid-cols-3"
                >
                    <div class="flex items-start gap-2.5">
                        <DocumentTextIcon class="mt-0.5 h-5 w-5 shrink-0 text-brand-lime" />
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-brand-lime-soft/90">
                                Certificate ID
                            </p>
                            <p class="mt-0.5 text-sm font-bold">{{ certificate.id }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <UserGroupIcon class="mt-0.5 h-5 w-5 shrink-0 text-brand-lime" />
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-brand-lime-soft/90">
                                Batch
                            </p>
                            <p class="mt-0.5 text-sm font-bold">{{ certificate.batch }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <CalendarDaysIcon class="mt-0.5 h-5 w-5 shrink-0 text-brand-lime" />
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-brand-lime-soft/90">
                                Completion Date
                            </p>
                            <p class="mt-0.5 text-sm font-bold">
                                {{ certificate.completionDate }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 flex items-start gap-2 rounded-xl bg-black/25 px-4 py-3 text-sm text-white/90 ring-1 ring-brand-lime/20 backdrop-blur-[2px] lg:mt-10"
                >
                    <CheckBadgeIcon class="mt-0.5 h-5 w-5 shrink-0 text-brand-lime" />
                    <p>
                        This certificate has been digitally verified and is authentic.
                        Issued by Skill Builders IT Institute.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div
                    class="relative flex h-full flex-col rounded-2xl bg-white p-5 text-slate-800 shadow-[0_12px_40px_rgba(0,0,0,0.25)] ring-1 ring-white/40 sm:flex-row sm:items-center sm:gap-5"
                >
                    <div
                        class="pointer-events-none absolute -inset-3 -z-10 rounded-3xl bg-brand-lime/20 blur-2xl"
                    />
                    <div
                        class="mx-auto flex h-40 w-40 shrink-0 items-center justify-center rounded-xl border border-slate-100 bg-slate-50 p-2 sm:mx-0"
                    >
                        <div class="relative">
                            <QrcodeVue
                                :value="certificate.verifyUrl"
                                :size="140"
                                level="H"
                                render-as="svg"
                                class="text-brand-navy"
                            />
                            <span
                                class="absolute left-1/2 top-1/2 flex h-9 w-9 -translate-x-1/2 -translate-y-1/2 items-center justify-center overflow-hidden rounded-md bg-white shadow ring-1 ring-slate-200"
                            >
                                <img
                                    :src="certificate.logo || '/images/logo.jpg'"
                                    alt="SB"
                                    class="h-7 w-7 object-contain"
                                />
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 flex-1 text-center sm:mt-0 sm:text-left">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Scan to Verify
                        </p>
                        <p class="mt-1 text-2xl font-extrabold tracking-wide text-brand-lime-dark">
                            VERIFIED
                        </p>
                        <p class="mt-1 text-sm font-medium text-slate-600">
                            Issued by Skill Builders IT Institute
                        </p>
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <img
                                v-if="certificate.signatureImage"
                                :src="certificate.signatureImage"
                                alt="Signature"
                                class="mx-auto h-10 object-contain sm:mx-0"
                            />
                            <p
                                v-else
                                class="font-serif text-lg italic leading-none text-brand-navy"
                                style="font-family: Georgia, 'Times New Roman', serif"
                            >
                                {{ certificate.signatureName }}
                            </p>
                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                Authorized Signature
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero-card {
    background-color: #0a2142;
}

.hero-card__gradient {
    background:
        radial-gradient(ellipse 80% 70% at 15% 20%, rgba(18, 48, 90, 0.75), transparent 55%),
        radial-gradient(ellipse 60% 50% at 85% 40%, rgba(6, 20, 40, 0.85), transparent 50%),
        linear-gradient(135deg, #12305a 0%, #0a2142 45%, #061428 100%);
}

.hero-card__glows {
    background:
        radial-gradient(circle at 12% 18%, rgba(141, 198, 63, 0.22), transparent 28%),
        radial-gradient(circle at 88% 45%, rgba(141, 198, 63, 0.16), transparent 32%),
        radial-gradient(circle at 55% 100%, rgba(10, 33, 66, 0.45), transparent 40%);
}
</style>
