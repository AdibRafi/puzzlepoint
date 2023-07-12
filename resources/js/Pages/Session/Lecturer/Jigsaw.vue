<template>
    <Head title="Jigsaw"/>
    <SessionLayout page-title="Jigsaw Session">
<!--        <Card title="DEVELOPER">-->
<!--            <button class="btn btn-primary" @click="buttonTest">Reload</button>-->
<!--            <p>{{ minuteCounter }}</p>-->
<!--        </Card>-->
        <div v-if="wizardStatus === 'onStartSession'"
             class="alert alert-info shadow-lg">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>
                  This is the Jigsaw Session. Here you can see the Time, Group formation, and Absent students
               </span>
            </div>
        </div>
        <TitleCard :title="'Jigsaw Session - '+props.topicModuleModal.name"
                   top-right-button-label="Add 1 Minute"
                   @button-function="addOneMinute">

            <TimerDisplayStatic :minute-counter="minuteCounter"
                                :second-counter="secondCounter"
                                :transition-minute-counter="transitionMinuteCounter"
                                :transition-second-counter="transitionSecondCounter"
                                :module-minute-counter="moduleMinuteCounter"
                                :module-second-counter="moduleSecondCounter"
                                :module-number="moduleNum"
                                session-type="Jigsaw Time"/>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="groupData in props.jigsawGroupUserModal">
                    <CardTable :title="groupData.name"
                               card-style="">
                        <div class="overflow-x-auto">
                            <table class="table w-full table-compact">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Module</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="userData in groupData.users" :key="userData">
                                    <td>{{ userData.name }}</td>
                                    <td>{{ userData.pivot.module_name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardTable>
                </div>
            </div>
            <div class="divider"/>
            <div :class="'float-right ' + (wizardStatus === 'onStartSession' ?
             'tooltip tooltip-open tooltip-info tooltip-bottom':'')"
                 :data-tip="(wizardStatus=== 'onStartSession' ?
                 'Click here to continue':'')">
                <button @click.prevent="endSession"
                        class="btn btn-primary">End Session
                </button>
            </div>
            <button class="btn btn-primary float-right mx-4"
                    @click="endModule">End Module
            </button>
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
            <h2 class="card-title">Total Students: {{props.studentAbsentModal.length + props.studentAttendModal.length}}</h2>
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
import {onMounted, onUnmounted, ref} from "vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import '../../../bootstrap'
import SessionLayout from "@/Layouts/SessionLayout.vue";
import CardTable from "@/Components/CardTable.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";

const props = defineProps({
    topicModuleModal: Object,
    jigsawGroupUserModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
    tm: Object,
})

const minuteCounter = ref(props.topicModuleModal.max_time_jigsaw);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(props.topicModuleModal.transition_time);
const transitionSecondCounter = ref(0);
const moduleMinuteCounter = ref(props.tm[0]);
const moduleSecondCounter = ref(0);
const moduleNum = ref(1);

const wizardStatus = usePage().props.auth.user.wizard_status

console.log(props.tm)

const addOneMinute = () => {
    minuteCounter.value++;
    moduleMinuteCounter.value++;
}
// const postTime = () => {
//     router.post(route('update.time', {
//         minuteCounter: minuteCounter.value,
//         secondCounter: secondCounter.value,
//         transitionMinuteCounter: transitionMinuteCounter.value,
//         transitionSecondCounter: transitionSecondCounter.value
//     }))
// }


const endSession = () => {
    router.get(route('lecturer.session.end'), {
        topic_id: props.topicModuleModal.id
    })
}

const endModule = () => {
    minuteCounter.value -= moduleMinuteCounter.value;
    secondCounter.value = 0;

    props.tm.shift();
    moduleMinuteCounter.value = props.tm[0];
    moduleNum.value++;
}

window.Echo.channel('student-attendance-channel')
    .listen('StudentAttendance', (e) => {
        console.log(e);
        router.reload();
    })


onMounted(() => {
    console.log('mounted')
})
const interval = setInterval(() => {
    if (transitionMinuteCounter.value === 0 && transitionSecondCounter.value === 0) {
        if (secondCounter.value > 0) {
            secondCounter.value--;

            moduleSecondCounter.value--;
        } else {
            secondCounter.value = 59;
            minuteCounter.value--;

            if (moduleMinuteCounter.value === 0 && moduleSecondCounter.value === 0) {
                props.tm.shift();
                moduleMinuteCounter.value = props.tm[0];
                moduleNum.value++;
            }

            moduleSecondCounter.value = 59;
            moduleMinuteCounter.value--;
        }
    } else {
        if (transitionSecondCounter.value > 0) {
            transitionSecondCounter.value--;
        } else {
            transitionSecondCounter.value = 59;
            transitionMinuteCounter.value--;
        }
    }

    router.post(route('update.time'), {
        sessionType: 'jigsaw',
        minuteCounter: minuteCounter.value,
        secondCounter: secondCounter.value,
        transitionMinuteCounter: transitionMinuteCounter.value,
        transitionSecondCounter: transitionSecondCounter.value,
        moduleMinuteCounter: moduleMinuteCounter.value,
        moduleSecondCounter: moduleSecondCounter.value,
        moduleNumber: moduleNum.value
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
