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
            :initiate-transition-minute="props.topicModal.transition_time"/>
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

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,
})

window.Echo.channel('move-session-channel')
    .listen('MoveSession', (e) => {
        if (e.message === 'goJigsaw') {
            router.get(route('student.session.jigsaw', {topic_id: props.topicModal.id }))
        }
    })

</script>
