<script setup>
defineProps({
    title: { type: String, required: true },
    value: { type: String, required: true },
    valueClass: { type: String, default: 'text-slate-900' },
    iconBg: { type: String, default: 'bg-indigo-50 text-indigo-700' },
    trend: { type: Boolean, default: false },
});
</script>

<template>
    <div
        class="flex items-center gap-4 rounded-xl border border-slate-200/80 bg-white px-5 py-4 shadow-soft"
    >
        <div
            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full"
            :class="iconBg"
        >
            <slot name="icon" />
        </div>

        <div class="min-w-0">
            <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-500">
                {{ title }}
            </p>
            <div class="mt-1 flex items-center gap-1.5">
                <p
                    class="text-2xl font-extrabold leading-none tracking-tight sm:text-[1.75rem]"
                    :class="valueClass"
                >
                    {{ value }}
                </p>
                <span
                    v-if="trend"
                    class="text-xs font-bold leading-none text-brand-lime-dark"
                    aria-hidden="true"
                >
                    ▲
                </span>
            </div>
        </div>
    </div>
</template>
