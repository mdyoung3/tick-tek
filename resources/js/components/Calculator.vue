<script setup>
import { ref } from "vue";

import { evaluate } from 'mathjs'

const display = ref("0");
const expression = ref("");
const firstNumber = ref(null);
const operator = ref(null);
const hasDecimal = ref(false);

// Spoken announcement for screen readers — only updated on completed calculations
const announcement = ref("");

const emit = defineEmits(["calculated"]);

/**
 * Handle number button presses.
 * Appends the number to the current display value.
 * Replaces the initial "0" with the first number pressed.
 */
const handleNumber = (number) => {
    if (display.value === "0") {
        display.value = String(number);
    } else {
        display.value += String(number);
    }
};

/**
 * Handle decimal point button press.
 * Prevents adding a second decimal to the current number.
 */
const handleDecimal = () => {
    if (!hasDecimal.value) {
        display.value += ".";
        hasDecimal.value = true;
    }
};


const handleOperator = (op) => {
    expression.value += display.value + op;
    display.value = "0";
    hasDecimal.value = false;
};

const handleOpenParen = () => {
    // If display has a real number, treat it as implicit multiplication: 9( → 9*(
    if (display.value !== "0") {
        expression.value += display.value + "*(";
    } else {
        expression.value += "(";
    }
    display.value = "0";
    hasDecimal.value = false;
};

const handleCloseParen = () => {
    expression.value += display.value + ")";
    display.value = "0";
    hasDecimal.value = false;
};

const handleSqrt = () => {
    expression.value += "sqrt(";
    // don't flush display — user types the number inside sqrt() next
};


function handleEquals() {
    const fullExpression = expression.value + display.value; // "9*9"
    try {
        const result = evaluate(fullExpression);
        announcement.value = `${fullExpression} equals ${result}`;
        display.value = String(result);
        emit('calculated', fullExpression, result);
        expression.value = "";  // reset for next calculation
        hasDecimal.value = false;
    } catch (e) {
        display.value = 'Error';
        announcement.value = 'Error';
    }
}

/**
 * Clear the calculator and reset all state back to defaults.
 */
const handleClear = () => {
    display.value = "0";
    expression.value = "";
    hasDecimal.value = false;
    announcement.value = "";
};
</script>

<template>
    <section aria-label="Calculator" class="w-full bg-zinc-950 shadow-[0_32px_80px_rgba(0,0,0,0.9)] border border-white/[0.15]">

        <!-- Gradient accent line (decorative) -->
        <div class="h-px bg-gradient-to-r from-cyan-500 via-cyan-400 to-violet-500" aria-hidden="true"></div>

        <p class="sr-only" aria-live="polite" aria-atomic="true">{{ announcement }}</p>

        <!-- Display area — labelled region, not a live region itself -->
        <div class="px-7 pt-8 pb-7" aria-label="Calculator display" role="region">
            <!--
                Expression line — zinc-400 on zinc-950 = 7.38:1, passes 1.4.3 AA
                (was zinc-600 = 1.77:1, FAIL)
            -->
            <p class="text-zinc-400 font-mono text-xs tracking-[0.25em] uppercase min-h-4" aria-hidden="true">
                {{ expression }}
            </p>
            <p class="text-white font-mono text-6xl font-black tracking-tight mt-3 truncate leading-none" aria-hidden="true">
                {{ display }}
            </p>
        </div>

        <div class="h-px bg-white/[0.12] mx-7" aria-hidden="true"></div>

        <!-- Button grid — gap-px creates hairline separators -->
        <div class="p-5 pt-6">
            <div class="grid grid-cols-4 gap-px bg-white/[0.1]" role="group" aria-label="Calculator buttons">

                <button
                    type="button"
                    @click="handleClear"
                    class="calc-btn-clear col-span-4"
                    aria-label="CLR — Clear calculator"
                >
                    CLR
                </button>

                <!-- Row 1 -->
                <button type="button" @click="handleNumber(7)" class="calc-btn-num">7</button>
                <button type="button" @click="handleNumber(8)" class="calc-btn-num">8</button>
                <button type="button" @click="handleNumber(9)" class="calc-btn-num">9</button>
                <button type="button" @click="handleOperator('/')" class="calc-btn-op" aria-label="Divide">÷</button>

                <!-- Row 2 -->
                <button type="button" @click="handleNumber(4)" class="calc-btn-num">4</button>
                <button type="button" @click="handleNumber(5)" class="calc-btn-num">5</button>
                <button type="button" @click="handleNumber(6)" class="calc-btn-num">6</button>
                <button type="button" @click="handleOperator('*')" class="calc-btn-op" aria-label="Multiply">×</button>

                <!-- Row 3 -->
                <button type="button" @click="handleNumber(1)" class="calc-btn-num">1</button>
                <button type="button" @click="handleNumber(2)" class="calc-btn-num">2</button>
                <button type="button" @click="handleNumber(3)" class="calc-btn-num">3</button>
                <button type="button" @click="handleOperator('-')" class="calc-btn-op" aria-label="Subtract">−</button>

                <!-- Row 4 -->
                <button type="button" @click="handleNumber(0)" class="calc-btn-num">0</button>
                <button type="button" @click="handleDecimal" class="calc-btn-num" aria-label="Decimal point">.</button>
                <button type="button" @click="handleEquals" class="calc-btn-eq" aria-label="Equals">
                    =
                </button>
                <button type="button" @click="handleOperator('+')" class="calc-btn-op" aria-label="Add">+</button>

                <!-- Function row -->
                <button type="button" @click="handleOpenParen" class="calc-btn-op" aria-label="Open parenthesis">(</button>
                <button type="button" @click="handleCloseParen" class="calc-btn-op" aria-label="Close parenthesis">)</button>
                <button type="button" @click="handleOperator('^')" class="calc-btn-op" aria-label="Power">^</button>
                <button type="button" @click="handleSqrt" class="calc-btn-op" aria-label="Square root">√</button>

            </div>
        </div>

    </section>
</template>

<style scoped>
@reference "../../css/app.css";


.calc-btn-num {
    @apply bg-zinc-950 hover:bg-zinc-800 active:bg-zinc-700
           text-white font-mono text-2xl font-semibold
           py-6 transition-colors duration-100 cursor-pointer
           relative focus-visible:z-10;
}

.calc-btn-op {
    @apply bg-zinc-950 hover:bg-zinc-800 active:bg-cyan-950
           text-cyan-400 font-mono text-2xl font-bold
           py-6 transition-colors duration-100 cursor-pointer
           relative focus-visible:z-10;
}

.calc-btn-clear {
    @apply bg-zinc-950 hover:bg-zinc-800 active:bg-red-950
           text-red-500 hover:text-red-400
           font-bold tracking-[0.35em] text-xs uppercase
           py-5 transition-colors duration-100 cursor-pointer
           border border-red-900 hover:border-red-700
           relative focus-visible:z-10;
}

.calc-btn-eq {
    @apply bg-cyan-500 hover:bg-cyan-400 active:bg-cyan-300
           text-zinc-950 font-mono text-2xl font-black
           py-6 transition-colors duration-100 cursor-pointer
           relative focus-visible:z-10;
}
</style>
