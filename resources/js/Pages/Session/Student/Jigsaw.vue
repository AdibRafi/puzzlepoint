<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>{{ displayTime }}</p>
            <p>{{ loop}}</p>
        </Card>
        <Card title="Jigsaw Session">
            <p>{{ props.topicModal.name }}</p>
        </Card>
        <TimerDisplay v-if="displayTime"
                      :initiate-minute="minuteCounter"
                      :initiate-second="secondCounter"
                      :initiate-transition-minute="transitionMinuteCounter"
                      :initiate-transition-second="transitionSecondCounter"/>
        <Card title="Your Group Members">
            <p v-for="userData in props.groupUserModal.users" :key="userData">
                {{ userData.name }}
            </p>
        </Card>
        <Card title="Display Slides here">

        </Card>
    </MainLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import {onMounted, ref} from "vue";
import '../../../bootstrap'


const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);
const transitionSecondCounter = ref(0);

const displayTime = ref();

//todo: why after student masuk (lepas attendance) dia loop?????
onMounted(()=>{
    console.log('mounted')
    displayTime.value = false;
})

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
});
const loop = ref(0)
window.Echo.channel('time-session-channel')
    .listen('TimeSession', (e) => {
        console.log(e);
        minuteCounter.value = e.minuteCounter;
        secondCounter.value = e.secondCounter;
        transitionMinuteCounter.value = e.transitionMinuteCounter;
        transitionSecondCounter.value = e.transitionSecondCounter;
        displayTime.value = true;
        loop.value++
    });
</script>
