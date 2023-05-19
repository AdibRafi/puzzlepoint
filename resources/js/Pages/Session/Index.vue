<template>
    <SessionLayout page-title="Index">
        <Card title="DEVELOPER">
            <!--            <p>{{ props.studentAbsentModal }}</p>-->
        </Card>
        <div v-if="$page.props.auth.user.type ==='student'">
            <Card title="You have enter a session">
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
        <div v-if="$page.props.auth.user.type === 'lecturer'">
            <Card :title="props.topicModuleModal.name">
                <p>Module: </p>
                <p v-for="moduleData in props.topicModuleModal.modules" :key="moduleData">
                    {{ moduleData.name }}
                </p>
                <p>Expert Time = {{ props.topicModuleModal.max_time_expert }}</p>
                <p>Jigsaw Time = {{ props.topicModuleModal.max_time_jigsaw }}</p>
                <p>Total Student = {{ props.studentAttendModal.length + props.studentAbsentModal.length }}</p>
            </Card>
            <Card :title="'Absent, '+props.studentAbsentModal.length+' students'">
                <p v-for="userData in props.studentAbsentModal" :key="userData"
                   class="text-red-500">{{ userData.name }}</p>
            </Card>
            <Card :title="'Present, '+props.studentAttendModal.length+' students'">
                <p v-for="userData in props.studentAttendModal" :key="userData">
                    {{ userData.name }}
                </p>
            </Card>
            <Card>
                <Link :href="route('lecturer.session.expert',{
                    topic_id:props.topicModuleModal.id,
                    minuteCounter: props.topicModuleModal.max_time_expert,
                    secondCounter: 0,
                    transitionMinuteCounter: props.topicModuleModal.transition_time,
                    transitionSecondCounter:0,

                })"
                      class="btn btn-primary">Start
                    Expert Session
                </Link>
            </Card>
        </div>
    </SessionLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../bootstrap'
import {Link, router, usePage} from "@inertiajs/vue3";
import SessionLayout from "@/Layouts/SessionLayout.vue";

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
})


window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        router.reload();
    })

if (usePage().props.auth.user.type === 'student') {
    window.Echo.channel('move-expert-channel')
        .listen('MoveExpertSession', (e) => {
            router.get(route('student.session.expert', {topic_id: props.topicModuleModal.id}))
        });
    window.Echo.channel('move-jigsaw-channel')
        .listen('MoveJigsawSession', (e) => {
            router.get(route('student.session.jigsaw', {topic_id: props.topicModuleModal.id}))
        })
}

</script>
