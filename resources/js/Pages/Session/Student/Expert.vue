<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>{{ props.moduleModal }}</p>
        </Card>
        <Card title="Expert Session">
            <p>{{ props.topicModal.name }}</p>
            <p>{{ props.moduleModal.name }}</p>
        </Card>
        <TimerDisplay
            :initiate-minute="props.topicModal.max_time_expert"
            :initiate-transition-minute="props.topicModal.transition_time" />
        <Card title="Your Group Members">
            <p v-for="userData in props.groupUserModal.users" :key="userData">{{ userData.name }}</p>
        </Card>
        <Card title="Display Slides here">

        </Card>
    </MainLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../../bootstrap'
import {ref} from "vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,
})

const minuteCounter = ref(props.topicModal.max_time_expert);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(props.topicModal.transition_time);
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
