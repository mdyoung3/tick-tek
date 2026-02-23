<script setup>
// Receive a single calculation object from TickerTape.vue
const props = defineProps({
    calculation: {
        type: Object,
        required: true,
    },
});

// Emit the delete event with the calculation ID up to TickerTape.vue
const emit = defineEmits(["delete"]);

/**
 * Emit the delete event with this calculation's ID.
 * The event bubbles up through TickerTape.vue to App.vue
 * where the actual API delete call is made.
 */
const handleDelete = () => {
    emit("delete", props.calculation.id);
};
</script>

<template>
    <div class="flex items-center justify-between bg-slate-800 hover:bg-slate-700 rounded-xl px-4 py-3 transition group">

        <!-- Expression and result -->
        <div>
            <p class="text-white font-mono text-sm">
                {{ calculation.expression }}
            </p>
            <p class="text-slate-400 text-xs mt-1">
                {{ new Date(calculation.created_at).toLocaleString() }}
            </p>
        </div>

        <!-- Delete button, only visible on hover -->
        <button
            @click="handleDelete"
            class="text-slate-500 hover:text-red-400 transition opacity-0 group-hover:opacity-100 ml-4 text-lg font-bold"
            title="Delete this calculation"
        >
            ✕
        </button>

    </div>
</template>
