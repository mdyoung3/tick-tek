<script setup>
// Receive calculations array from App.vue
import CalculationBubble from "./CalculationBubble.vue";

const props = defineProps({
    calculations: {
        type: Array,
        required: true,
    },
});


// Emit delete and clear-all events up to App.vue
const emit = defineEmits(["delete", "clear-all"]);

/**
 * Emit the delete event with the calculation ID up to App.vue.
 * App.vue will handle the actual API call.
 */
const handleDelete = (id) => {
    emit("delete", id);
};

/**
 * Emit the clear-all event up to App.vue.
 * App.vue will handle deleting all calculations from the API.
 */
const handleClearAll = () => {
    emit("clear-all");
};

</script>

<template>
    <div class="w-full mt-10">

        <!-- Section header -->
        <div class="flex items-center justify-between mb-5 px-1">
            <div class="flex items-center gap-3">
                <!-- Cyan accent mark -->
                <div class="w-px h-4 bg-cyan-500"></div>
                <h3>History</h3>
            </div>
            <button
                v-if="calculations.length > 0"
                @click="handleClearAll"
                class="text-xs text-zinc-700 hover:text-red-500 tracking-[0.2em] uppercase font-bold transition-colors duration-150"
            >
                Clear All
            </button>
        </div>

        <!-- Empty state -->
        <div
            v-if="calculations.length === 0"
            class="border border-dashed border-white/[0.07] py-14 text-center"
        >
            <p class="text-zinc-700 text-xs tracking-[0.25em] uppercase">No calculations yet</p>
            <p class="text-zinc-800 text-xs tracking-[0.15em] uppercase mt-2">Start calculating above</p>
        </div>

        <!-- Calculation list — gap-px creates thin separators -->
        <div v-else class="flex flex-col gap-px bg-white/[0.04] border border-white/[0.06]">
            <CalculationBubble
                v-for="calculation in calculations"
                :key="calculation.id"
                :calculation="calculation"
                @delete="handleDelete"
            />
        </div>

    </div>
</template>
