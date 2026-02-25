<script setup>
import { ref } from "vue";

const display = ref("0");
const expression = ref("");
const firstNumber = ref(null);
const operator = ref(null);
const hasDecimal = ref(false);

// Spoken announcement for screen readers — only updated on completed calculations
// (WCAG 4.1.3: status messages must be programmatically exposed)
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
            if (secondNumber === 0) {
                display.value = "Error";
                // Announce error to screen readers (WCAG 4.1.3)
                announcement.value = "Error: cannot divide by zero";
                return;
            }
            result = firstNumber.value / secondNumber;
            break;
    }

    const fullExpression = `${firstNumber.value} ${operator.value} ${secondNumber} = ${result}`;
    expression.value = fullExpression;
    display.value = String(result);

    // Announce the completed calculation to screen readers (WCAG 4.1.3)
    announcement.value = fullExpression;

    emit("calculated", fullExpression, result);

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
    announcement.value = "";
};
</script>

<template>
    <!--
        section landmark with aria-label gives screen reader users a named region
        they can jump to directly (WCAG 1.3.1, 2.4.1)
    -->
    <section aria-label="Calculator" class="w-full bg-zinc-950 shadow-[0_32px_80px_rgba(0,0,0,0.9)] border border-white/[0.06]">

        <!-- Gradient accent line (decorative) -->
        <div class="h-px bg-gradient-to-r from-cyan-500 via-cyan-400 to-violet-500" aria-hidden="true"></div>

        <!--
            aria-live="polite" + aria-atomic="true": announces completed calculations
            and error states to screen readers without interrupting ongoing speech.
            Kept in a visually hidden element so the display div itself doesn't
            re-announce on every digit press. (WCAG 4.1.3)
        -->
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

        <div class="h-px bg-white/[0.05] mx-7" aria-hidden="true"></div>

        <!-- Button grid — gap-px creates hairline separators -->
        <div class="p-5 pt-6">
            <div class="grid grid-cols-4 gap-px bg-white/[0.05]" role="group" aria-label="Calculator buttons">

                <!--
                    All buttons have type="button" to prevent form submission side-effects.
                    Accessible names satisfy WCAG 4.1.2 (Name, Role, Value).
                    WCAG 2.5.3 (Label in Name): abbreviation "CLR" is present in aria-label.
                -->
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
                <!--
                    Symbolic characters (÷ × − +) are exempt from WCAG 2.5.3 per the
                    spec note: "Symbolic text characters... do not need to follow this rule."
                -->
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

            </div>
        </div>

    </section>
</template>

<style scoped>
@reference "../../css/app.css";

/*
 * All button classes include focus-visible: z-10 + relative so the
 * global focus ring (defined in app.css) is not clipped by adjacent
 * buttons in the gap-px grid. (WCAG 2.4.7)
 *
 * Contrast checks against bg-zinc-950 (#09090b, L≈0.006):
 *   text-white:    ~18:1  ✓
 *   text-cyan-400: ~11:1  ✓
 *   text-red-500:   ~4.9:1 ✓
 *   text-zinc-950 on bg-cyan-500: ~8.1:1 ✓
 */

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
    @apply bg-zinc-950 hover:bg-zinc-900 active:bg-red-950
           text-red-500 hover:text-red-400
           font-bold tracking-[0.35em] text-xs uppercase
           py-5 transition-colors duration-100 cursor-pointer
           relative focus-visible:z-10;
}

.calc-btn-eq {
    @apply bg-cyan-500 hover:bg-cyan-400 active:bg-cyan-300
           text-zinc-950 font-mono text-2xl font-black
           py-6 transition-colors duration-100 cursor-pointer
           relative focus-visible:z-10;
}
</style>
