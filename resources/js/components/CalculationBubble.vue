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

            <p class="text-zinc-400 text-xs mt-1.5 tracking-[0.15em] uppercase font-mono">
                {{ new Date(calculation.created_at).toLocaleString() }}
            </p>
        </div>

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
