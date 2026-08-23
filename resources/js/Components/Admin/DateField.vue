<script setup>
import { computed, ref } from 'vue';
import { CalendarDaysIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: { type: String, default: '' },
    label: { type: String, required: true },
    required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);
const inputRef = ref(null);

const displayValue = computed(() => {
    if (!props.modelValue) {
        return 'Select date';
    }

    try {
        return new Intl.DateTimeFormat('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }).format(new Date(`${props.modelValue}T00:00:00`));
    } catch {
        return props.modelValue;
    }
});

const openPicker = () => {
    const el = inputRef.value;
    if (!el) {
        return;
    }
    if (typeof el.showPicker === 'function') {
        el.showPicker();
    } else {
        el.focus();
        el.click();
    }
};
</script>

<template>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <button
            type="button"
            class="group relative flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-left shadow-sm transition hover:border-brand-navy/40 hover:shadow focus:outline-none focus:ring-2 focus:ring-brand-navy/20"
            @click="openPicker"
        >
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-navy/5 text-brand-navy transition group-hover:bg-brand-lime-muted"
            >
                <CalendarDaysIcon class="h-5 w-5" />
            </span>
            <span class="min-w-0 flex-1">
                <span
                    class="block text-sm font-semibold"
                    :class="modelValue ? 'text-slate-900' : 'text-slate-400'"
                >
                    {{ displayValue }}
                </span>
                <span class="block text-[11px] font-medium uppercase tracking-wide text-slate-400">
                    {{ modelValue || 'YYYY-MM-DD' }}
                </span>
            </span>

            <input
                ref="inputRef"
                type="date"
                class="pointer-events-none absolute inset-0 h-full w-full cursor-pointer opacity-0"
                :value="modelValue"
                :required="required"
                @input="emit('update:modelValue', $event.target.value)"
                @change="emit('update:modelValue', $event.target.value)"
            />
        </button>
    </div>
</template>
