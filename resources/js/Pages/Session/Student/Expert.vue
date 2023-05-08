<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>{{ minuteCounter }}</p>
        </Card>
        <Card title="Expert Session">
            <p>{{ props.topicModal.name }}</p>
            <p>{{ props.moduleModal.name }}</p>
        </Card>
        <TimerDisplayStatic :minute-counter="minuteCounter"
                      :second-counter="secondCounter"
                      :transition-minute-counter="transitionMinuteCounter"
                      :transition-second-counter="transitionSecondCounter"/>
        <Card title="Your Group Members">
            <p v-for="userData in props.groupUserModal.users" :key="userData">{{ userData.name }}</p>
        </Card>
        <Card title="Display Slides here">
<!--            <VuePdfEmbed :source="props.moduleModal.file_path"/>-->
            <VuePdfEmbed :source="{
                cMapURL:'public/modules',
                url:'M1.pdf'
            }"/>
        </Card>
    </MainLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../../bootstrap'
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";
import VuePdfEmbed from "vue-pdf-embed";

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,
})

console.log(props.moduleModal.file_path);

const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);

const transitionSecondCounter = ref(0);

window.Echo.channel('move-jigsaw-channel')
    .listen('MoveJigsawSession', (e) => {
        router.get(route('student.session.jigsaw', {topic_id: props.topicModal.id}))
    });

window.Echo.channel('time-session-channel')
    .listen('TimeSession', (e) => {
        // console.log(e);
        minuteCounter.value = e.minuteCounter;
        secondCounter.value = e.secondCounter;
        transitionMinuteCounter.value = e.transitionMinuteCounter;
        transitionSecondCounter.value = e.transitionSecondCounter;
    });

</script>
