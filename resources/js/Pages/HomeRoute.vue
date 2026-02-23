<script setup>
import axios from "axios";
import {ref, TriggerOpTypes} from "vue";

const response = ref();

const getValue = async () => {
    try {
        response.value = await axios.get("/api/test-me");
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <div>
        <h1>
            Tick-tek Calculator
        </h1>
        <button @click.prevent="getValue">Trigger Endpoint</button>
        <p v-if="response">{{ response.data }}</p>
    </div>

</template>
