<script setup>
import {
    CheckBadgeIcon,
    LockClosedIcon,
    ShieldCheckIcon,
} from '@heroicons/vue/24/solid';

defineProps({
    verifyUrl: {
        type: String,
        required: true,
    },
    logo: {
        type: String,
        default: null,
    },
});

const features = [
    {
        title: 'Authentic',
        text: 'Issued by authorized institute',
        icon: ShieldCheckIcon,
    },
    {
        title: 'Verified',
        text: 'Digitally verified record',
        icon: CheckBadgeIcon,
    },
    {
        title: 'Secure',
        text: 'Tamper-proof certification',
        icon: LockClosedIcon,
    },
];
</script>

<template>
    <section class="auth-footer relative overflow-hidden rounded-2xl p-6 text-white shadow-card sm:p-8">
        <div
            class="pointer-events-none absolute inset-0 opacity-40"
            style="
                background:
                    radial-gradient(circle at 12% 50%, rgba(141, 198, 63, 0.18), transparent 35%),
                    radial-gradient(circle at 88% 40%, rgba(141, 198, 63, 0.12), transparent 30%);
            "
        />

        <div
            class="relative grid items-center gap-8 lg:grid-cols-[1.2fr_auto_1fr] lg:gap-6"
        >
            <!-- Left: title + features -->
            <div>
                <h3 class="text-xl font-extrabold tracking-wide sm:text-2xl">
                    AUTHENTIC CERTIFICATE
                </h3>
                <p class="mt-2 text-sm text-white/75">
                    This certificate is digitally verified and trusted.
                </p>

                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div
                        v-for="item in features"
                        :key="item.title"
                        class="flex items-start gap-2.5"
                    >
                        <span
                            class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/10 ring-1 ring-brand-lime/30"
                        >
                            <component
                                :is="item.icon"
                                class="h-4 w-4 text-brand-lime"
                            />
                        </span>
                        <div>
                            <p class="text-sm font-bold text-white">{{ item.title }}</p>
                            <p class="mt-0.5 text-xs leading-snug text-white/60">
                                {{ item.text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: brand logo -->
            <div class="flex justify-center">
                <div
                    class="flex h-36 w-36 items-center justify-center rounded-2xl bg-white p-3 shadow-[0_0_40px_rgba(141,198,63,0.25)] ring-1 ring-brand-lime/40 sm:h-40 sm:w-40"
                >
                    <img
                        :src="logo || '/images/logo.jpg'"
                        alt="SkillBuilders IT Institute"
                        class="h-full w-full object-contain"
                    />
                </div>
            </div>

            <!-- Right: verify CTA -->
            <div
                class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm"
            >
                <p class="text-base font-bold text-white">Verify this certificate</p>
                <p class="mt-2 text-sm leading-relaxed text-white/70">
                    You can verify the authenticity of this certificate anytime using the
                    QR code or Certificate ID.
                </p>
                <p class="mt-4 text-sm font-bold text-brand-lime">{{ verifyUrl }}</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
.auth-footer {
    background:
        radial-gradient(ellipse 70% 80% at 20% 50%, rgba(0, 55, 56, 0.55), transparent 55%),
        linear-gradient(135deg, #003638 0%, #002425 50%, #001617 100%);
}
</style>
