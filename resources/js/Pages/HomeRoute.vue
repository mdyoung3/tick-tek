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
        <h2>
            Tick-tek
        </h2>

        <router-link to="/test">Take me ot Test page. </router-link>

        <button @click.prevent="getValue">Trigger Endpoint</button>
        <p v-if="response">{{ response.data }}</p>
    </div>

</template>
