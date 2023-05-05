<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>{{ minuteCounter }}</p>
        </Card>
        <Card title="Expert Session">
            <p>{{ props.topicModal.name }}</p>
            <p>{{ props.moduleModal.name }}</p>
        </Card>
        <TimerDisplay v-if="displayTime"
            :initiate-minute="minuteCounter"
            :initiate-second="secondCounter"
            :initiate-transition-minute="transitionMinuteCounter"
            :initiate-transition-second="transitionSecondCounter"/>
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
import TimerDisplay from "@/Components/TimerDisplay.vue";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const displayTime = ref(false);
const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);
const transitionSecondCounter = ref(0);

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,
})

window.Echo.channel('move-session-channel')
    .listen('MoveSession', (e) => {
        if (e.message === 'goJigsaw') {
            router.get(route('student.session.jigsaw', {topic_id: props.topicModal.id}))
        }
    });

window.Echo.channel('time-session-channel')
    .listen('TimeSession', (e) => {
        console.log(e);
        minuteCounter.value = e.minuteCounter;
        secondCounter.value = e.secondCounter;
        transitionMinuteCounter.value = e.transitionMinuteCounter;
        transitionSecondCounter.value = e.transitionSecondCounter;
        displayTime.value = true;
    });

</script>
