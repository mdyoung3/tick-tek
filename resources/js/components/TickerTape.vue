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
    <div class="w-full max-w-sm mx-auto mt-6">

        <div class="flex items-center justify-between mb-4">
            <h3>Ticker Tape</h3>
            <button
                v-if="calculations.length > 0"
                @click="handleClearAll"
                class="text-sm text-red-400 hover:text-red-300 transition"
            >
                Clear All
            </button>
        </div>

        <div
            v-if="calculations.length === 0"
            class="text-center text-slate-500 py-8 bg-slate-800 rounded-2xl"
        >
            <p>No calculations yet.</p>
            <p class="text-sm mt-1">Start calculating above!</p>
        </div>
        <div v-else class="flex flex-col gap-2">
            <CalculationBubble
                v-for="calculation in calculations"
                :key="calculation.id"
                :calculation="calculation"
                @delete="handleDelete"
            />
        </div>

    </div>
</template>
