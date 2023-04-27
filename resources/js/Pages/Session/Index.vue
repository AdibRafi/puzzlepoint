<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <!--            <p>{{ props.studentAbsentModal }}</p>-->
        </Card>
        <div v-if="$page.props.user.type ==='student'">
            <Card title="You have enter a session" class="my-2">
                Please wait for your lecturer to start the session
                <div class="divider">Topic</div>
                <p>Title: {{ props.topicModuleModal.name }}</p>
                <p v-for="moduleData in props.topicModuleModal.modules" :key="moduleData">
                    {{ moduleData.name }}
                </p>
                <p>Expert Time = {{ props.topicModuleModal.max_time_expert }}</p>
                <p>Jigsaw Time = {{ props.topicModuleModal.max_time_jigsaw }}</p>
            </Card>
        </div>
        <div v-if="$page.props.user.type === 'lecturer'">
            <Card :title="props.topicModuleModal.name" class="my-4">
                <p>Module: </p>
                <p v-for="moduleData in props.topicModuleModal.modules" :key="moduleData">
                    {{ moduleData.name }}
                </p>
                <p>Expert Time = {{ props.topicModuleModal.max_time_expert }}</p>
                <p>Jigsaw Time = {{ props.topicModuleModal.max_time_jigsaw }}</p>
                <p>Total Student = {{ props.studentAttendModal.length + props.studentAbsentModal.length }}</p>
            </Card>
            <Card :title="'Absent, '+props.studentAbsentModal.length+' students'" class="my-4">
                <p v-for="userData in props.studentAbsentModal" :key="userData"
                   class="text-red-500">{{ userData.name }}</p>
            </Card>
            <Card :title="'Present, '+props.studentAttendModal.length+' students'"
                  class="my-4">
                <p v-for="userData in props.studentAttendModal" :key="userData">
                    {{ userData.name }}
                </p>
            </Card>
            <Card>
                <Link :href="route('lecturer.session.expert',{topic_id:props.topicModuleModal.id})"
                      class="btn btn-primary">Start
                    Expert Session
                </Link>
            </Card>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../bootstrap'
import {Link, router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
})


window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        console.log(e);
        router.reload();
    })

window.Echo.channel('move-session-channel')
    .listen('MoveSession', (e) => {
        if (usePage().props.user.type === 'student' && e.message === 'goExpert') {
            router.get(route('student.session.expert', {topic_id: props.topicModuleModal.id}))
        }
        console.log(e);
    });
</script>

<style scoped>

</style>
