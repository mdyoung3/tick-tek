<script setup>
import CalculationBubble from "./CalculationBubble.vue";

const props = defineProps({
    calculations: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["delete", "clear-all"]);

const handleDelete = (id) => {
    emit("delete", id);
};

const handleClearAll = () => {
    emit("clear-all");
};
</script>

<template>
    <!--
        section landmark with aria-label = named region for screen reader navigation.
        (WCAG 1.3.1, 2.4.1)
    -->
    <section aria-label="Calculation history" class="w-full mt-10">

        <!-- Section header -->
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-3">
                <div class="w-px h-4 bg-cyan-500" aria-hidden="true"></div>
                <!--
                    Changed from h3 to h2: the page h1 is "Ticker Tape" in App.vue,
                    so this section heading is correctly at the second level.
                    Heading levels must not skip (WCAG 1.3.1).
                    Visual style matches the former h3 treatment via explicit classes.
                    zinc-400 on zinc-950 = 7.38:1 ✓ (WCAG 1.4.3)
                -->
                <h2 class="text-xs font-bold tracking-[0.28em] uppercase text-zinc-400">History</h2>
            </div>

            <!--
                aria-label="Clear all calculations" gives screen reader users a full
                description. type="button" prevents accidental form submission.
                zinc-400 default (7.38:1 ✓), red-500 hover (4.86:1 ✓) — WCAG 1.4.3
                (was zinc-700 = 1.41:1, FAIL)
            -->
            <button
                v-if="calculations.length > 0"
                type="button"
                @click="handleClearAll"
                aria-label="Clear all calculations"
                class="text-xs text-zinc-400 hover:text-red-500 tracking-[0.2em] uppercase font-bold transition-colors duration-150"
            >
                Clear All
            </button>
        </div>

        <!-- Empty state -->
        <div
            v-if="calculations.length === 0"
            class="border border-dashed border-white/[0.07] py-14 text-center"
            role="status"
            aria-label="No calculations yet"
        >
            <!--
                zinc-400 on zinc-950 = 7.38:1 ✓ — WCAG 1.4.3
                (was zinc-700 = 1.41:1 and zinc-800 = 1.14:1, both FAIL)
            -->
            <p class="text-zinc-400 text-xs tracking-[0.25em] uppercase">No calculations yet</p>
            <p class="text-zinc-400 text-xs tracking-[0.15em] uppercase mt-2">Start calculating above</p>
        </div>

        <!--
            WCAG 1.3.1 fix: changed from <div> to <ul> so screen readers announce
            "list, N items" — giving users immediate context about the content structure.
            list-none removes browser default bullet/padding on the ul element.
            Each <li> wraps a CalculationBubble; its implicit listitem role is correct.
        -->
        <ul
            v-else
            class="list-none m-0 p-0 flex flex-col gap-px bg-white/[0.04] border border-white/[0.06]"
            aria-label="Calculation list"
        >
            <li v-for="calculation in calculations" :key="calculation.id">
                <CalculationBubble
                    :calculation="calculation"
                    @delete="handleDelete"
                />
            </li>
        </ul>

    </section>
</template>
