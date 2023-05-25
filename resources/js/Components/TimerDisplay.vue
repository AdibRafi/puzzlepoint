<template>
    <!--    <Card title="Timer">-->
    <!--        <p>Normal</p>-->
    <!--        <p class="justify-center flex text-2xl">{{ minuteCounter }}:{{ secondCounter }}</p>-->
    <!--        <p>Transition</p>-->
    <!--        <p class="justify-center flex text-2xl">{{ transitionMinuteCounter }}:{{ transitionSecondCounter }}</p>-->
    <!--    </Card>-->
    <div class="stats shadow bg-base-100 w-full border-2 border-secondary">
        <div class="stat">
            <div class="stat-figure text-secondary">
                <font-awesome-icon icon="fa-solid fa-hourglass-half" size="xl"
                                   :spin="transitionMinuteCounter === 0 && transitionSecondCounter === 0"/>
            </div>
            <div class="stat-title">Expert Time</div>
            <div class="stat-value">{{ minuteCounter }}:{{ secondCounter }}</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-secondary">
                <font-awesome-icon icon="fa-solid fa-hourglass-half" size="xl"
                                   :spin="transitionMinuteCounter !== 0 || transitionSecondCounter !== 0"/>
            </div>
            <div class="stat-title">Transition Time</div>
            <div class="stat-value">{{ transitionMinuteCounter }}:{{ transitionSecondCounter }}</div>
        </div>
    </div>
</template>

<script setup>

import Card from "@/Components/Card.vue";
import {ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    initiateMinute: Number,
    initiateTransitionMinute: Number,
    initiateSecond: Number,
    initiateTransitionSecond: Number,
})

const minuteCounter = ref(props.initiateMinute);
const secondCounter = ref(props.initiateSecond);
const transitionMinuteCounter = ref(props.initiateTransitionMinute);
const transitionSecondCounter = ref(props.initiateTransitionSecond);

setInterval(() => {
    if (transitionMinuteCounter.value <= 0 && transitionSecondCounter.value <= 0) {
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
