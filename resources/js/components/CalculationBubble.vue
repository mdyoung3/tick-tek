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
    <div class="flex items-center justify-between px-6 py-5 bg-zinc-950 hover:bg-zinc-900 border-l-2 border-l-transparent hover:border-l-cyan-500 transition-all duration-150 group">

        <!-- Expression and result -->
        <div class="min-w-0">
            <p class="text-white font-mono text-sm font-medium truncate">
                {{ calculation.expression }}
            </p>
            <p class="text-zinc-700 text-xs mt-1.5 tracking-[0.15em] uppercase font-mono">
                {{ new Date(calculation.created_at).toLocaleString() }}
            </p>
        </div>

        <!-- Delete button, only visible on hover -->
        <button
            @click="handleDelete"
            class="text-zinc-700 hover:text-red-500 transition-colors duration-150 opacity-0 group-hover:opacity-100 ml-5 text-xs tracking-[0.2em] uppercase font-bold shrink-0"
            title="Delete this calculation"
        >
            DEL
        </button>

    </div>
</template>
