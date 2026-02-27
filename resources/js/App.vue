<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Calculator from "./components/Calculator.vue";
import TickerTape from "./components/TickerTape.vue";

const calculations = ref([]);

/**
 * Fetch all calculations from the API when the component mounts.
 * This populates the ticker tape on page load with existing history.
 */
onMounted(async () => {
    await fetchCalculations();
});

/**
 * Retrieve all calculations from the Laravel API.
 * Called on mount and after any create/delete operation
 * to keep the ticker tape in sync with the database.
 */
const fetchCalculations = async () => {
    try {
        const response = await axios.get("/api/calculations");
        calculations.value = response.data;
    } catch (error) {
        console.error("Error fetching calculations:", error);
    }
};

/**
 * Save a new calculation to the database.
 * Triggered when the Calculator component emits a 'calculated' event.
 * The event payload contains the expression and result.
 */
const saveCalculation = async (expression, result) => {
    try {
        await axios.post("/api/calculations", {
            expression,
            result,
        });
        // Refresh the ticker tape after saving
        await fetchCalculations();
    } catch (error) {
        console.error("Error saving calculation:", error);
    }
};

/**
 * Delete a single calculation by its ID.
 * Triggered when a CalculationBubble emits a 'delete' event.
 */
const deleteCalculation = async (id) => {
    try {
        await axios.delete(`/api/calculations/${id}`);
        // Refresh the ticker tape after deleting
        await fetchCalculations();
    } catch (error) {
        console.error("Error deleting calculation:", error);
    }
};

/**
 * Clear all calculations from the database.
 * Triggered when TickerTape emits a 'clear-all' event.
 */
const clearAllCalculations = async () => {
    try {
        await axios.delete("/api/calculations");
        calculations.value = [];
    } catch (error) {
        console.error("Error clearing calculations:", error);
    }
};
</script>

<template>
    <main class="min-h-screen bg-zinc-950 py-14 px-4">
        <div class="w-full max-w-sm mx-auto">

            <div class="mb-10">
                <p class="text-cyan-500 font-mono text-xs tracking-[0.3em] uppercase mb-3">CALC · 2026</p>
                <h1 class="text-5xl font-black tracking-tighter leading-none text-white uppercase">
                    Tape Calc
                </h1>
                <div class="mt-5 h-px bg-gradient-to-r from-cyan-500 via-cyan-400/40 to-transparent"></div>
            </div>

            <Calculator @calculated="saveCalculation" />

            <TickerTape
                :calculations="calculations"
                @delete="deleteCalculation"
                @clear-all="clearAllCalculations"
            />

        </div>
    </main>
</template>
