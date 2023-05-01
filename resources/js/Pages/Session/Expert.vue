<template>
    <Head title="Expert"/>
    <MainLayout>
        <Card title="DEVELOPER">
            <button class="btn btn-primary" @click="buttonTest">Reload</button>
        </Card>
        <Card title="Expert Session">
            <p>{{ props.topicModuleModal.name }}</p>
        </Card>
        <Card title="Timer">
            <p>Normal</p>
            <p class="justify-center flex text-2xl">{{ minuteCounter }}:{{ secondCounter }}</p>
            <p>Transition</p>
            <p class="justify-center flex text-2xl">{{ transitionMinuteCounter }}:{{ transitionSecondCounter }}</p>
        </Card>
        <Card :title="'Absent, '+props.studentAbsentModal.length+' students'">
            <p v-for="userData in props.studentAbsentModal" :key="userData"
               class="text-red-500">{{ userData.name }}</p>
        </Card>
        <div v-for="groupData in props.expertGroupUserModal">
            <Card :title="groupData.name">
                <div v-for="userData in groupData.users">
                    <p>{{ userData.name }}</p>
                </div>
            </Card>
        </div>
        <Card>
            <Link :href="route('lecturer.session.jigsaw',
            {topic_id:props.topicModuleModal.id})"
                  class="btn btn-primary">
                Next to Jigsaw
            </Link>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import '../../bootstrap'

//todo: everytime got attendance, -> update database -> (student) get time from database and start from there
window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        router.reload();
    })

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
    expertGroupUserModal: Object,

})

const minuteCounter = ref(props.topicModuleModal.max_time_expert);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(props.topicModuleModal.transition_time);
const transitionSecondCounter = ref(0);

const buttonTest = () => {
    router.reload();
    // router.get(route('session.move.expert', {topic_id: props.topicModuleModal.id,
    //     user: usePage().props.user.name}));
}

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

<style scoped>

</style>
