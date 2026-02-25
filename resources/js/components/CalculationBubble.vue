<script setup>
const props = defineProps({
    calculation: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["delete"]);

const handleDelete = () => {
    emit("delete", props.calculation.id);
};
</script>

<template>
    <div
        class="flex items-center justify-between px-6 py-5 bg-zinc-950 hover:bg-zinc-900 border-l-2 border-l-transparent hover:border-l-cyan-500 transition-all duration-150 group"
    >

        <!-- Expression and timestamp -->
        <div class="min-w-0">
            <p class="text-white font-mono text-sm font-medium truncate">
                {{ calculation.expression }}
            </p>
            <!--
                zinc-400 on zinc-950 = 7.38:1 — passes 1.4.3 AA
                (was zinc-700 = 1.41:1, FAIL)
            -->
            <p class="text-zinc-400 text-xs mt-1.5 tracking-[0.15em] uppercase font-mono">
                {{ new Date(calculation.created_at).toLocaleString() }}
            </p>
        </div>

        <!--
            WCAG 2.1.1 fix: was opacity-0 with no focus fallback, meaning keyboard users
            could tab to an invisible button. Now uses focus-visible:opacity-100 so the
            button is always reachable and visible via keyboard.

            WCAG 1.4.3 fix: text-zinc-400 (7.38:1) when visible — was zinc-700 (1.41:1, FAIL).
            On hover bg-zinc-900: zinc-400 = 7.01:1 ✓, red-500 hover = 4.62:1 ✓

            WCAG 4.1.2 fix: aria-label identifies both the abbreviation "DEL" (satisfying
            2.5.3 Label in Name) and what will be deleted, giving screen reader users context.

            title attribute removed — unreliable across assistive technologies.
        -->
        <button
            type="button"
            @click="handleDelete"
            :aria-label="`DEL — Delete: ${calculation.expression}`"
            class="text-zinc-400 hover:text-red-500 transition-colors duration-150
                   opacity-0 group-hover:opacity-100 focus-visible:opacity-100
                   ml-5 text-xs tracking-[0.2em] uppercase font-bold shrink-0"
        >
            DEL
        </button>

    </div>
</template>
