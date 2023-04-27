<template>
    <Card title="Timer">
        <p>Normal</p>
        <p class="justify-center flex text-2xl">{{ minuteCounter }}:{{ secondCounter }}</p>
        <p>Transition</p>
        <p class="justify-center flex text-2xl">{{ transitionMinuteCounter }}:{{ transitionSecondCounter }}</p>
    </Card>
</template>

<script setup>

import Card from "@/Components/Card.vue";
import {ref} from "vue";

const props = defineProps({
    initiateMinute: Number,
    initiateTransitionMinute:Number,
})

const minuteCounter = ref(props.initiateMinute);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(props.initiateTransitionMinute);
const transitionSecondCounter = ref(0);

setInterval(() => {
    if (transitionMinuteCounter.value === 0 && transitionSecondCounter.value === 0) {
        if (secondCounter.value > 0) {
            secondCounter.value--;
        } else {
            secondCounter.value = 59;
            minuteCounter.value--;
        }
    } else {
        if (transitionSecondCounter.value > 0) {
            transitionSecondCounter.value--;
        } else {
            transitionSecondCounter.value = 59;
            transitionMinuteCounter.value--;
        }
    }
}, 1000)

</script>

<style scoped>

</style>
