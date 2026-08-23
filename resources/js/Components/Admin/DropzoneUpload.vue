<script setup>
import { computed, ref, watch } from 'vue';
import {
    ArrowUpTrayIcon,
    DocumentIcon,
    PhotoIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: { type: [File, null], default: null },
    label: { type: String, required: true },
    hint: { type: String, default: 'PNG, JPG up to 2MB' },
    accept: { type: String, default: 'image/*' },
    existingUrl: { type: String, default: null },
    existingLabel: { type: String, default: 'Current file' },
});

const emit = defineEmits(['update:modelValue']);

const dragging = ref(false);
const previewUrl = ref(null);
const inputRef = ref(null);

const isImageAccept = computed(() => props.accept.includes('image'));

watch(
    () => props.modelValue,
    (file) => {
        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
            previewUrl.value = null;
        }
        if (file instanceof File && file.type.startsWith('image/')) {
            previewUrl.value = URL.createObjectURL(file);
        }
    },
);

const onFiles = (files) => {
    const file = files?.[0];
    if (!file) {
        return;
    }
    emit('update:modelValue', file);
};

const onDrop = (event) => {
    dragging.value = false;
    onFiles(event.dataTransfer.files);
};

const clear = () => {
    emit('update:modelValue', null);
    if (inputRef.value) {
        inputRef.value.value = '';
    }
};
</script>

<template>
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">{{ label }}</label>

        <div
            class="relative overflow-hidden rounded-2xl border-2 border-dashed transition"
            :class="
                dragging
                    ? 'border-brand-lime bg-brand-lime-muted/40'
                    : 'border-slate-200 bg-slate-50/80 hover:border-brand-navy/40 hover:bg-white'
            "
            @dragenter.prevent="dragging = true"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="onDrop"
        >
            <input
                ref="inputRef"
                type="file"
                class="absolute inset-0 z-10 cursor-pointer opacity-0"
                :accept="accept"
                @change="onFiles($event.target.files)"
            />

            <div class="flex flex-col items-center px-4 py-8 text-center">
                <div
                    v-if="previewUrl || (existingUrl && isImageAccept && !modelValue)"
                    class="mb-3 overflow-hidden rounded-xl bg-white p-2 shadow-sm ring-1 ring-slate-200"
                >
                    <img
                        :src="previewUrl || existingUrl"
                        :alt="label"
                        class="h-20 w-20 object-contain"
                    />
                </div>
                <div
                    v-else
                    class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-brand-navy shadow-sm ring-1 ring-slate-200"
                >
                    <PhotoIcon v-if="isImageAccept" class="h-7 w-7" />
                    <DocumentIcon v-else class="h-7 w-7" />
                </div>

                <p class="text-sm font-semibold text-slate-800">
                    <span class="text-brand-navy">Click to upload</span>
                    or drag &amp; drop
                </p>
                <p class="mt-1 text-xs text-slate-500">{{ hint }}</p>

                <div
                    v-if="modelValue"
                    class="relative z-20 mt-3 inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-200"
                >
                    <ArrowUpTrayIcon class="h-3.5 w-3.5 text-brand-lime-dark" />
                    <span class="max-w-[160px] truncate">{{ modelValue.name }}</span>
                    <button
                        type="button"
                        class="rounded-full p-0.5 text-slate-400 hover:bg-slate-100 hover:text-red-500"
                        @click.stop.prevent="clear"
                    >
                        <XMarkIcon class="h-3.5 w-3.5" />
                    </button>
                </div>

                <a
                    v-else-if="existingUrl"
                    :href="existingUrl"
                    target="_blank"
                    class="relative z-20 mt-3 text-xs font-semibold text-brand-navy hover:underline"
                    @click.stop
                >
                    {{ existingLabel }}
                </a>
            </div>
        </div>
    </div>
</template>
