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
    <section aria-label="Calculation history" class="w-full mt-10">

        <!-- Section header -->
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-3">
                <div class="w-px h-4 bg-cyan-500" aria-hidden="true"></div>
                <h2 class="text-xs font-bold tracking-[0.28em] uppercase text-zinc-400">History</h2>
            </div>

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
            <p class="text-zinc-400 text-xs tracking-[0.25em] uppercase">No calculations yet</p>
            <p class="text-zinc-400 text-xs tracking-[0.15em] uppercase mt-2">Start calculating above</p>
        </div>

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
