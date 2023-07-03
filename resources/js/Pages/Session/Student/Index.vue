<template>
    <Head title="Session"/>
    <MainLayout>
        <TitleCard>

        </TitleCard>
        <Card title="You have enter a session" class="my-4">
            Please wait your lecturer to start the topic session
        </Card>
    </MainLayout>
</template>

<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../../bootstrap'
import TitleCard from "@/Components/TitleCard.vue";

const props = defineProps({
    topicModuleModal: Object,
})

console.log('test');
window.Echo.channel('move-session-channel')
    .listen('MoveSession', (e) => {
        if (e.message === 'goExpert') {
            router.get(route('student.session.expert', {topic_id: props.topicModuleModal.id}))
        } else if (e.message === 'goJigsaw') {
            router.get(route('student.session.jigsaw', {topic_id: props.topicModuleModal.id}))
        }
        console.log(e + 'test');
    });

</script>

<style scoped>

</style>
