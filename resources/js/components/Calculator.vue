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
    <div class="w-full max-w-sm mx-autobg-slate-800 rounded-2xl shadow-2xl p-6">

        <!-- Display Screen -->
        <div class="bg-slate-950 rounded-xl p-4 mb-4 text-right">
            <p class="text-slate-400 text-sm min-h-5">{{ expression }}</p>
            <p class="text-white text-4xl font-mono font-bold truncate">{{ display }}</p>
        </div>

        <!-- Button Grid -->
        <div class="grid grid-cols-4 gap-3">

            <!-- Clear button spans full width -->
            <button
                @click="handleClear"
                class="col-span-4 bg-red-600 hover:bg-red-500 text-white font-bold py-3 rounded-xl transition"
            >
                C
            </button>

            <!-- Number buttons 7-9 and divide -->
            <button @click="handleNumber(7)" class="calc-btn">7</button>
            <button @click="handleNumber(8)" class="calc-btn">8</button>
            <button @click="handleNumber(9)" class="calc-btn">9</button>
            <button @click="handleOperator('/')" class="calc-btn-operator">/</button>

            <!-- Number buttons 4-6 and multiply -->
            <button @click="handleNumber(4)" class="calc-btn">4</button>
            <button @click="handleNumber(5)" class="calc-btn">5</button>
            <button @click="handleNumber(6)" class="calc-btn">6</button>
            <button @click="handleOperator('*')" class="calc-btn-operator">*</button>

            <!-- Number buttons 1-3 and subtract -->
            <button @click="handleNumber(1)" class="calc-btn">1</button>
            <button @click="handleNumber(2)" class="calc-btn">2</button>
            <button @click="handleNumber(3)" class="calc-btn">3</button>
            <button @click="handleOperator('-')" class="calc-btn-operator">-</button>

            <!-- Zero, decimal, equals and add -->
            <button @click="handleNumber(0)" class="calc-btn">0</button>
            <button @click="handleDecimal" class="calc-btn">.</button>
            <button @click="handleEquals"
                    class="col-span-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition">=
            </button>
            <button @click="handleOperator('+')" class="calc-btn-operator">+</button>

        </div>
    </div>
</template>

<style scoped>
@reference "../../css/app.css";
.calc-btn {
    @apply bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 rounded-xl transition;
}

.calc-btn-operator {
    @apply bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 rounded-xl transition;
}
</style>
