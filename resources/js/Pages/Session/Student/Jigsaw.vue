<template>
    <MainLayout>
        <Card title="DEVELOPER">
<!--            <p>{{ props.groupUserModal }}</p>-->
        </Card>
        <Card title="Jigsaw Session">
            <p>{{ props.topicModal.name }}</p>
        </Card>
        <TimerDisplay
            :initiate-minute="props.topicModal.max_time_jigsaw"
            :initiate-transition-minute="props.topicModal.transition_time"/>
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

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
});
</script>
