<script setup>
defineProps({
    title: { type: String, required: true },
    columns: { type: Array, required: true },
    rows: { type: Array, required: true },
    barColor: { type: String, default: 'bg-emerald-500' },
});
</script>

<template>
    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
        <h3 class="text-base font-bold text-brand-navy">{{ title }}</h3>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[480px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wide text-slate-400">
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            class="pb-3 font-semibold"
                            :class="col.align === 'right' ? 'text-right' : ''"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in rows"
                        :key="index"
                        class="border-b border-slate-50 last:border-0"
                    >
                        <td class="py-3.5 pr-3 font-semibold text-slate-700">
                            {{ row.name }}
                        </td>
                        <td class="py-3.5 pr-3 text-slate-600">{{ row.obtained }}</td>
                        <td class="py-3.5 pr-3 text-slate-600">{{ row.total }}</td>
                        <td class="py-3.5 pr-3">
                            <div class="flex min-w-[110px] items-center gap-2">
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full"
                                        :class="barColor"
                                        :style="{ width: `${row.percentage}%` }"
                                    />
                                </div>
                                <span class="w-10 text-right text-xs font-semibold text-slate-500">
                                    {{ row.percentage }}%
                                </span>
                            </div>
                        </td>
                        <td
                            v-if="row.grade !== undefined"
                            class="py-3.5 text-right font-bold text-brand-navy"
                        >
                            {{ row.grade }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
