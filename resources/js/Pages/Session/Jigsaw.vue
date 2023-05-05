<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <button class="btn btn-primary" @click="buttonTest">Reload</button>
        </Card>
        <Card title="Jigsaw Session" class="my-4">
            <p>{{ props.topicModuleModal.name }}</p>
        </Card>
        <Card title="Timer" class="my-4">
            <p>Normal</p>
            <p class="justify-center flex text-2xl">{{ minuteCounter }}:{{ secondCounter }}</p>
            <p>Transition</p>
            <p class="justify-center flex text-2xl">{{ transitionMinuteCounter }}:{{ transitionSecondCounter }}</p>
        </Card>
        <Card :title="'Absent, '+props.studentAbsentModal.length+' students'" class="my-4">
            <p v-for="userData in props.studentAbsentModal" :key="userData"
               class="text-red-500">{{ userData.name }}</p>
        </Card>
        <div v-for="groupData in props.jigsawGroupUserModal">
            <Card :title="groupData.name" class="my-4">
                <div v-for="userData in groupData.users">
                    <p>{{ userData.name }}</p>
                </div>
            </Card>
        </div>
        <Card>
            <button class="btn btn-primary">End Session</button>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";
import '../../bootstrap'

const props = defineProps({
    topicModuleModal: Object,
    jigsawGroupUserModal: Object,
    studentAbsentModal: Object,
})

const minuteCounter = ref(props.topicModuleModal.max_time_jigsaw);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(props.topicModuleModal.transition_time);
const transitionSecondCounter = ref(0);
const postTime = () => {
    router.post(route('update.time', {
        minuteCounter: minuteCounter.value,
        secondCounter: secondCounter.value,
        transitionMinuteCounter: transitionMinuteCounter.value,
        transitionSecondCounter: transitionSecondCounter.value
    }))
}

const buttonTest = () => {
    console.log('nice');
    router.reload();
    postTime()
}

window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        console.log(e);
        router.reload();
        setTimeout(postTime, 5000)
    })


onMounted(() => {
    console.log('mounted')
    setTimeout(postTime, 3000)
})
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
