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
defineProps({
    minuteCounter: Number,
    secondCounter: Number,
    transitionMinuteCounter: Number,
    transitionSecondCounter: Number,
})
</script>
