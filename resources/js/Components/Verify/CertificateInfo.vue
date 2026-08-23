<script setup>
defineProps({
    info: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-soft sm:p-6">
        <h3 class="text-base font-bold text-brand-navy">Certificate Information</h3>

        <dl class="mt-5 grid gap-x-8 gap-y-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Certificate ID
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.id }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Course Name
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.courseName }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Batch
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.batch }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Duration
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.duration }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Enrollment Date
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.enrollmentDate }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Completion Date
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.completionDate }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Issue Date
                </dt>
                <dd class="mt-1 text-sm font-bold text-slate-800">{{ info.issueDate }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Status
                </dt>
                <dd class="mt-1">
                    <span
                        class="inline-flex items-center rounded-full bg-brand-lime-muted px-3 py-1 text-xs font-bold text-brand-lime-dark ring-1 ring-brand-lime/40"
                    >
                        Valid &amp; Authentic
                    </span>
                </dd>
            </div>
        </dl>
    </div>
</template>
