<template>
    <Head title="Expert"/>
    <SessionLayout page-title="Expert Session">
        <!--        <Card title="DEVELOPER">-->
        <!--            <button class="btn btn-primary" @click="buttonTest">Reload</button>-->
        <!--            <p>{{ minuteCounter }}</p>-->
        <!--        </Card>-->
        <div v-if="wizardStatus === 'onStartSession'"
             class="alert alert-info shadow-lg">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>
                  This is the Expert Session. Here you can see the Time, Group formation, and Absent students
               </span>
            </div>
        </div>
        <TitleCard :title="'Expert Session - '+props.topicModuleModal.name"
                   top-right-button-label="Add 1 Minute"
                   @button-function="addOneMinute">
            <TimerDisplayStatic :minute-counter="minuteCounter" :second-counter="secondCounter"
                                :transition-minute-counter="transitionMinuteCounter"
                                :transition-second-counter="transitionSecondCounter"
                                session-type="Expert Time"/>
            <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                <div v-for="groupData in props.expertGroupUserModal">
                    <CardTable :title="groupData.name"
                               card-style="">
                        <p>{{ groupData.module.name }}</p>
                        <div class="overflow-x-auto">
                            <table class="table w-full table-compact">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="userData in groupData.users" :key="userData">
                                    <td>{{ userData.name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardTable>
                </div>
            </div>
            <div class="divider"/>
            <div :class="'float-right ' + (wizardStatus === 'onStartSession' ?
             'tooltip tooltip-open tooltip-info tooltip-left':'')"
                 :data-tip="(wizardStatus=== 'onStartSession' ?
                 'Click here to continue':'')">
                <button class="btn btn-primary" @click.prevent="toJigsawSession">
                    Proceed to Jigsaw Session
                </button>
            </div>
        </TitleCard>
        <div v-if="wizardStatus === 'onStartSession'"
             class="alert alert-info shadow-lg mt-4">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>
                  Here you can see the list of student that present or absent
               </span>
            </div>
        </div>
        <TitleCard title="Student List">
            <h2 class="card-title">Total Students:
                {{ props.studentAbsentModal.length + props.studentAttendModal.length }}</h2>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                    <tr>
                        <th class="w-3/4">Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="studentData in props.studentAbsentModal" :key="studentData">
                        <td>{{ studentData.name }}</td>
                        <td>
                            <div class="badge badge-error">
                                Absent
                            </div>
                        </td>
                    </tr>
                    <tr v-for="studentData in props.studentAttendModal" :key="studentData">
                        <td>{{ studentData.name }}</td>
                        <td>
                            <div class="badge badge-success">
                                Present
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </TitleCard>
    </SessionLayout>
</template>

<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {onMounted, onUnmounted, ref} from "vue";
import '../../../bootstrap'
import SessionLayout from "@/Layouts/SessionLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import TitleCard from "@/Components/TitleCard.vue";
import CardTable from "@/Components/CardTable.vue";
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";

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

const wizardStatus = usePage().props.auth.user.wizard_status;

window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        // router.reload({
        //     data: {
        //         minuteCounter: minuteCounter.value,
        //         secondCounter: secondCounter.value,
        //         transitionMinuteCounter: transitionMinuteCounter.value,
        //         transitionSecondCounter: transitionSecondCounter.value
        //     }
        // });
        // setTimeout(postTime, 5000);
    })


const addOneMinute = () => {
    minuteCounter.value++;
    console.log(minuteCounter.value)
}

const toJigsawSession = () => {
    router.get(route('lecturer.session.jigsaw'), {
        topic_id: props.topicModuleModal.id
    });
}

onMounted(() => {
    console.log('mounted')
})

const interval = setInterval(() => {
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
    console.log('idk')
    router.post(route('update.time'), {
        sessionType: 'expert',
        minuteCounter: minuteCounter.value,
        secondCounter: secondCounter.value,
        transitionMinuteCounter: transitionMinuteCounter.value,
        transitionSecondCounter: transitionSecondCounter.value
    }, {
        preserveScroll: true,
    });

}, 1000)

onUnmounted(() => {
    clearInterval(interval);
})
</script>

<style scoped>

</style>
