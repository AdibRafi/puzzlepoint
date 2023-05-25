<template>
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
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
defineProps({
    minuteCounter: Number,
    secondCounter: Number,
    transitionMinuteCounter: Number,
    transitionSecondCounter: Number,
})
</script>
