<template>
    <SessionLayout>
        <Card title="DEVELOPER">
            <!--            <p>{{props.groupUserModal}}</p>-->
        </Card>
        <Card title="Jigsaw Session">
            <p>{{ props.topicModal.name }}</p>
        </Card>
        <TimerDisplayStatic :minute-counter="minuteCounter"
                            :second-counter="secondCounter"
                            :transition-minute-counter="transitionMinuteCounter"
                            :transition-second-counter="transitionSecondCounter"
                            :module-minute-counter="moduleMinuteCounter"
                            :module-second-counter="moduleSecondCounter"
                            :module-number="moduleNum"/>

        <Card title="Your Group Members">
            <p v-for="userData in props.groupUserModal.users" :key="userData">
                {{ userData.name }}
            </p>
        </Card>
        <Card title="Display Slides here">

        </Card>
    </SessionLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import {onMounted, ref} from "vue";
import '../../../bootstrap'
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";
import {router} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import SessionLayout from "@/Layouts/SessionLayout.vue";


const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);
const transitionSecondCounter = ref(0);
const moduleMinuteCounter = ref(0);
const moduleSecondCounter = ref(0);
const moduleNum = ref();


onMounted(() => {
    console.log('mounted')
})

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
});
window.Echo.channel('update-jigsaw-session')
    .listen('UpdateJigsawSession', (e) => {
        console.log(e);
        minuteCounter.value = e.minuteCounter;
        secondCounter.value = e.secondCounter;
        transitionMinuteCounter.value = e.transitionMinuteCounter;
        transitionSecondCounter.value = e.transitionSecondCounter;
        moduleMinuteCounter.value = e.moduleMinuteCounter;
        moduleSecondCounter.value = e.moduleSecondCounter;
        moduleNum.value = e.moduleNumber;
    });

window.Echo.channel('end-session-channel')
    .listen('EndSession', (e) => {
        router.get(route('student.session.end'), {topic_id: props.topicModal.id})
    })
</script>
