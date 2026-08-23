<script setup>
defineProps({
    certificate: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="certificate-sheet relative mx-auto aspect-[1.414/1] w-full max-w-[1000px] overflow-hidden bg-[#fbfaf6] text-[#0A2142] shadow-2xl"
    >
        <div class="absolute inset-3 rounded-sm border-[3px] border-[#0A2142]" />
        <div class="absolute inset-5 rounded-sm border border-[#8DC63F]" />
        <div class="absolute inset-7 border border-[rgba(10,33,66,0.2)]" />

        <div class="pointer-events-none absolute inset-0">
            <div class="absolute left-8 top-8 h-16 w-16 border-l-2 border-t-2 border-[#8DC63F]" />
            <div class="absolute right-8 top-8 h-16 w-16 border-r-2 border-t-2 border-[#8DC63F]" />
            <div class="absolute bottom-8 left-8 h-16 w-16 border-b-2 border-l-2 border-[#8DC63F]" />
            <div class="absolute bottom-8 right-8 h-16 w-16 border-b-2 border-r-2 border-[#8DC63F]" />
        </div>

        <div class="relative flex h-full flex-col items-center px-10 py-8 sm:px-14 sm:py-10">
            <img
                :src="certificate.logo || '/images/logo.jpg'"
                alt="SkillBuilders"
                class="h-16 w-16 object-contain sm:h-20 sm:w-20"
            />

            <p class="mt-3 text-[10px] font-bold uppercase tracking-[0.35em] text-[#8DC63F] sm:text-xs">
                SkillBuilders IT Institute
            </p>

            <h1
                class="mt-4 text-3xl font-bold tracking-wide text-[#0A2142] sm:text-4xl md:text-5xl"
                style="font-family: Georgia, 'Times New Roman', serif"
            >
                Certificate of Completion
            </h1>

            <div class="mt-3 h-px w-40 bg-gradient-to-r from-transparent via-[#8DC63F] to-transparent" />

            <p class="mt-6 text-sm text-slate-600 sm:text-base">This is to certify that</p>

            <p
                class="mt-2 text-center text-3xl font-bold italic text-[#0A2142] sm:text-4xl md:text-5xl"
                style="font-family: Georgia, 'Times New Roman', serif"
            >
                {{ certificate.studentName }}
            </p>

            <p class="mt-5 max-w-2xl text-center text-sm leading-relaxed text-slate-600 sm:text-base">
                has successfully completed the professional training program
            </p>

            <p class="mt-2 text-center text-xl font-extrabold text-[#0A2142] sm:text-2xl">
                {{ certificate.courseName }}
            </p>

            <div
                class="mt-6 grid w-full max-w-3xl grid-cols-2 gap-3 text-center text-xs sm:grid-cols-4 sm:text-sm"
            >
                <div class="rounded-lg bg-white/70 px-2 py-2 ring-1 ring-[rgba(10,33,66,0.1)]">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                        Certificate ID
                    </p>
                    <p class="mt-1 font-bold">{{ certificate.id }}</p>
                </div>
                <div class="rounded-lg bg-white/70 px-2 py-2 ring-1 ring-[rgba(10,33,66,0.1)]">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                        Batch
                    </p>
                    <p class="mt-1 font-bold">{{ certificate.batch || '—' }}</p>
                </div>
                <div class="rounded-lg bg-white/70 px-2 py-2 ring-1 ring-[rgba(10,33,66,0.1)]">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                        Completion
                    </p>
                    <p class="mt-1 font-bold">{{ certificate.completionDate || '—' }}</p>
                </div>
                <div class="rounded-lg bg-white/70 px-2 py-2 ring-1 ring-[rgba(10,33,66,0.1)]">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                        Grade
                    </p>
                    <p class="mt-1 font-bold">{{ certificate.grade || '—' }}</p>
                </div>
            </div>

            <div class="mt-auto flex w-full max-w-3xl items-end justify-between gap-6 pt-8">
                <div class="min-w-[140px] text-center">
                    <img
                        v-if="certificate.signatureImage"
                        :src="certificate.signatureImage"
                        alt="Signature"
                        class="mx-auto mb-1 h-10 object-contain"
                    />
                    <p
                        v-else
                        class="mb-1 text-lg italic"
                        style="font-family: Georgia, 'Times New Roman', serif"
                    >
                        {{ certificate.signatureName || 'Skill Builders' }}
                    </p>
                    <div class="mx-auto h-px w-36 bg-[rgba(10,33,66,0.4)]" />
                    <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        Authorized Signature
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-[#8DC63F]">
                        Issued on
                    </p>
                    <p class="mt-1 text-sm font-bold">{{ certificate.issueDate || '—' }}</p>
                    <p class="mt-2 text-[10px] text-slate-400">
                        Verify: {{ certificate.verifyUrlDisplay }}
                    </p>
                </div>

                <div class="min-w-[140px] text-center">
                    <p class="text-lg font-extrabold text-[#0A2142]">
                        {{
                            certificate.overallScore != null
                                ? certificate.overallScore + '%'
                                : '—'
                        }}
                    </p>
                    <div class="mx-auto h-px w-36 bg-[rgba(10,33,66,0.4)]" />
                    <p class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        Overall Score
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
