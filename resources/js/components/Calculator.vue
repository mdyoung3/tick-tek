<script setup>
import { ref } from "vue";

// What's shown on the calculator display
const display = ref("0");

// Stores the full expression string e.g. "9 + 3"
const expression = ref("");

// Tracks the first number in the calculation
const firstNumber = ref(null);

// Tracks the selected operator
const operator = ref(null);

// Prevents multiple decimals in a single number
const hasDecimal = ref(false);

// Emits the calculated event up to App.vue with expression and result
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

/**
 * Handle operator button presses (+, -, *, /).
 * Stores the first number and selected operator,
 * then clears the display for the second number input.
 */
const handleOperator = (op) => {
    firstNumber.value = parseFloat(display.value);
    operator.value = op;
    hasDecimal.value = false;
    display.value = "0";
};

/**
 * Handle the equals button press.
 * Performs the calculation based on the stored operator,
 * updates the display with the result, builds the expression
 * string, and emits the 'calculated' event up to App.vue.
 */
const handleEquals = () => {
    if (firstNumber.value === null || operator.value === null) return;

    const secondNumber = parseFloat(display.value);
    let result = null;

    switch (operator.value) {
        case "+":
            result = firstNumber.value + secondNumber;
            break;
        case "-":
            result = firstNumber.value - secondNumber;
            break;
        case "*":
            result = firstNumber.value * secondNumber;
            break;
        case "/":
            // Guard against division by zero
            if (secondNumber === 0) {
                display.value = "Error";
                return;
            }
            result = firstNumber.value / secondNumber;
            break;
    }

    // Build the expression string for the ticker tape
    const fullExpression = `${firstNumber.value} ${operator.value} ${secondNumber} = ${result}`;
    expression.value = fullExpression;

    // Update the display with the result
    display.value = String(result);

    // Emit up to App.vue to save to the database
    emit("calculated", fullExpression, result);

    // Reset state for the next calculation
    firstNumber.value = null;
    operator.value = null;
    hasDecimal.value = false;
};

/**
 * Clear the calculator and reset all state back to defaults.
 */
const handleClear = () => {
    display.value = "0";
    expression.value = "";
    firstNumber.value = null;
    operator.value = null;
    hasDecimal.value = false;
};
</script>

<template>
    <div class="w-full bg-zinc-950 shadow-[0_32px_80px_rgba(0,0,0,0.9)] border border-white/[0.06]">

        <!-- Gradient accent line at top -->
        <div class="h-px bg-gradient-to-r from-cyan-500 via-cyan-400 to-violet-500"></div>

        <!-- Display Screen -->
        <div class="px-7 pt-8 pb-7">
            <p class="text-zinc-600 font-mono text-xs tracking-[0.25em] uppercase min-h-4">
                {{ expression || '&nbsp;' }}
            </p>
            <p class="text-white font-mono text-6xl font-black tracking-tight mt-3 truncate leading-none">
                {{ display }}
            </p>
        </div>

        <!-- Thin divider -->
        <div class="h-px bg-white/[0.05] mx-7"></div>

        <!-- Button Grid — gap-px creates hairline borders between buttons -->
        <div class="p-5 pt-6">
            <div class="grid grid-cols-4 gap-px bg-white/[0.05]">

                <!-- Clear — full width -->
                <button
                    @click="handleClear"
                    class="calc-btn-clear col-span-4"
                >
                    CLR
                </button>

                <!-- Row 1: 7 8 9 ÷ -->
                <button @click="handleNumber(7)" class="calc-btn-num">7</button>
                <button @click="handleNumber(8)" class="calc-btn-num">8</button>
                <button @click="handleNumber(9)" class="calc-btn-num">9</button>
                <button @click="handleOperator('/')" class="calc-btn-op">÷</button>

                <!-- Row 2: 4 5 6 × -->
                <button @click="handleNumber(4)" class="calc-btn-num">4</button>
                <button @click="handleNumber(5)" class="calc-btn-num">5</button>
                <button @click="handleNumber(6)" class="calc-btn-num">6</button>
                <button @click="handleOperator('*')" class="calc-btn-op">×</button>

                <!-- Row 3: 1 2 3 − -->
                <button @click="handleNumber(1)" class="calc-btn-num">1</button>
                <button @click="handleNumber(2)" class="calc-btn-num">2</button>
                <button @click="handleNumber(3)" class="calc-btn-num">3</button>
                <button @click="handleOperator('-')" class="calc-btn-op">−</button>

                <!-- Row 4: 0 . = + -->
                <button @click="handleNumber(0)" class="calc-btn-num">0</button>
                <button @click="handleDecimal" class="calc-btn-num">.</button>
                <button @click="handleEquals" class="calc-btn-eq">=</button>
                <button @click="handleOperator('+')" class="calc-btn-op">+</button>

            </div>
        </div>

    </div>
</template>

<style scoped>
@reference "../../css/app.css";

.calc-btn-num {
    @apply bg-zinc-950 hover:bg-zinc-800 active:bg-zinc-700
           text-white font-mono text-2xl font-semibold
           py-6 transition-colors duration-100 cursor-pointer;
}

.calc-btn-op {
    @apply bg-zinc-950 hover:bg-zinc-800 active:bg-cyan-950
           text-cyan-400 font-mono text-2xl font-bold
           py-6 transition-colors duration-100 cursor-pointer;
}

.calc-btn-clear {
    @apply bg-zinc-950 hover:bg-zinc-900 active:bg-red-950
           text-red-500 hover:text-red-400
           font-bold tracking-[0.35em] text-xs uppercase
           py-5 transition-colors duration-100 cursor-pointer;
}

.calc-btn-eq {
    @apply bg-cyan-500 hover:bg-cyan-400 active:bg-cyan-300
           text-zinc-950 font-mono text-2xl font-black
           py-6 transition-colors duration-100 cursor-pointer;
}
</style>
