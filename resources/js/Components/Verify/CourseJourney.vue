<script setup>
import {
    AcademicCapIcon,
    BookOpenIcon,
    ClipboardDocumentCheckIcon,
    DocumentCheckIcon,
    RocketLaunchIcon,
    TrophyIcon,
} from '@heroicons/vue/24/solid';

defineProps({
    milestones: {
        type: Array,
        required: true,
    },
    studentName: {
        type: String,
        required: true,
    },
    courseName: {
        type: String,
        required: true,
    },
});

const icons = [
    RocketLaunchIcon,
    BookOpenIcon,
    AcademicCapIcon,
    ClipboardDocumentCheckIcon,
    DocumentCheckIcon,
    TrophyIcon,
];
</script>

<template>
    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
        <h3 class="text-base font-bold text-brand-navy">Course Journey</h3>

        <div class="mt-6 overflow-x-auto pb-2">
            <div class="relative flex min-w-[640px] items-start justify-between gap-2 px-2">
                <div
                    class="absolute left-8 right-8 top-5 h-0.5 bg-gradient-to-r from-brand-navy via-brand-lime to-brand-lime-dark"
                />
                <div
                    v-for="(item, index) in milestones"
                    :key="item.label"
                    class="relative z-10 flex w-24 flex-col items-center text-center"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full text-white shadow-md"
                        :class="
                            index === milestones.length - 1
                                ? 'bg-brand-lime text-brand-navy'
                                : index % 2 === 0
                                  ? 'bg-brand-navy'
                                  : 'bg-brand-lime-dark'
                        "
                    >
                        <component :is="icons[index] || TrophyIcon" class="h-5 w-5" />
                    </div>
                    <p class="mt-3 text-xs font-bold text-brand-navy">{{ item.label }}</p>
                    <p class="mt-1 text-[11px] font-medium text-slate-500">{{ item.date }}</p>
                </div>
            </div>
        </div>

        <div
            class="mt-6 flex items-start gap-4 rounded-2xl border border-brand-lime/30 bg-gradient-to-r from-brand-lime-muted to-white p-4"
        >
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-lime text-brand-navy shadow"
            >
                <TrophyIcon class="h-7 w-7" />
            </div>
            <div>
                <p class="text-base font-extrabold text-brand-navy">Congratulations!</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-600">
                    {{ studentName }} has successfully completed
                    <span class="font-semibold text-slate-800">{{ courseName }}</span>
                    with outstanding performance and is certified by Skill Builders IT
                    Institute.
                </p>
            </div>
        </div>
    </div>
</template>
