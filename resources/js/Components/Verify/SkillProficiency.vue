<script setup>
import { computed } from 'vue';
import { Radar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend,
);

const props = defineProps({
    skills: {
        type: Array,
        required: true,
    },
});

const chartData = computed(() => ({
    labels: props.skills.map((s) => s.name),
    datasets: [
        {
            label: 'Proficiency',
            data: props.skills.map((s) => s.percentage),
            backgroundColor: 'rgba(141, 198, 63, 0.18)',
            borderColor: '#8DC63F',
            borderWidth: 2,
            pointBackgroundColor: '#0A2142',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#8DC63F',
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
    },
    scales: {
        r: {
            beginAtZero: true,
            max: 100,
            ticks: {
                display: false,
                stepSize: 20,
            },
            grid: {
                color: 'rgba(148, 163, 184, 0.35)',
            },
            angleLines: {
                color: 'rgba(148, 163, 184, 0.35)',
            },
            pointLabels: {
                font: {
                    size: 10,
                    weight: '600',
                },
                color: '#64748b',
            },
        },
    },
};
</script>

<template>
    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
        <h3 class="text-base font-bold text-brand-navy">Skill Proficiency</h3>

        <div class="mt-5 grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <div v-for="skill in skills" :key="skill.name">
                    <div class="mb-1.5 flex items-center justify-between text-sm">
                        <span class="font-semibold text-slate-700">{{ skill.name }}</span>
                        <span class="font-bold text-brand-lime-dark">{{ skill.percentage }}%</span>
                    </div>
                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full bg-brand-lime"
                            :style="{ width: `${skill.percentage}%` }"
                        />
                    </div>
                </div>
            </div>

            <div class="relative h-64 w-full lg:h-auto lg:min-h-[260px]">
                <Radar :data="chartData" :options="chartOptions" />
            </div>
        </div>
    </div>
</template>
