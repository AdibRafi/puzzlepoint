<template>
    <Head title="Index"/>
    <SessionLayout page-title="Pre Session">
        <div v-if="$page.props.auth.user.type === 'lecturer'">
            <TitleCard :title="props.topicModuleModal.name">
                <div v-if="wizardStatus === 'onStartSession'"
                     class="alert alert-info shadow-lg mb-4">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span>
                  Since this is a dummy student, Everyone will be present <br/>
                     Scroll down to continue
               </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th class="w-3/4">Name</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="studentData in props.studentAttendModal" :key="studentData">
                            <td>{{ studentData.name }}</td>
                            <td>
                                <div class="badge badge-success">
                                    Present
                                </div>
                            </td>
                        </tr>
                        <tr v-for="studentData in props.studentAbsentModal" :key="studentData">
                            <td>{{ studentData.name }}</td>
                            <td>
                                <div class="badge badge-error">
                                    Absent
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="divider mb-6"/>
                <div class="float-left shadow bg-base-100 border-2 w-80">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass" size="xl"/>
                        </div>
                        <div class="stat-title">Time After Buffer</div>
                        <div class="stat-value">{{ props.timeAddBuffer }}</div>
                        <div class="stat-desc">
                            You can start early if you like
                        </div>
                    </div>
                </div>
                <div :class="'float-right ' +
                (wizardStatus === 'onStartSession' ?
                'tooltip tooltip-open tooltip-info tooltip-left':'')"
                     :data-tip="(wizardStatus=== 'onStartSession' ?
                 'Click here to continue':'')">
                    <button class="btn btn-primary" @click="proceedExpert">Proceed to Expert Session</button>
                </div>
            </TitleCard>
        </div>
        <div v-if="$page.props.auth.user.type ==='student'">
            <TitleCard title="You have enter a session">
                <h2 class="card-title">Please Wait for your lecturer to start the session</h2>
                <div class="divider"/>
                <GridLayout>
                    <Stat title="Topic Title" :value="props.topicModuleModal.name"/>
                    <Stat title="Expert Session Duration" :value="props.topicModuleModal.max_time_expert + ' Minutes'" figure="fa-solid fa-hourglass"/>
                    <Stat title="Jigsaw Session Duration" :value="props.topicModuleModal.max_time_jigsaw + ' Minutes'" figure="fa-solid fa-hourglass"/>
                </GridLayout>
                <div class="divider">Module</div>
                <GridLayout>
                    <Stat v-for="(moduleData,index) in props.topicModuleModal.modules" :key="moduleData"
                          :title="'Module ' + (index + 1)" :value="moduleData.name"/>
                </GridLayout>
            </TitleCard>

        </div>
    </SessionLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../bootstrap'
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import SessionLayout from "@/Layouts/SessionLayout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import GridLayout from "@/Components/GridLayout.vue";
import Stat from "@/Components/Stat.vue";

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
    timeAddBuffer: Object,
})

const wizardStatus = usePage().props.auth.user.wizard_status

const proceedExpert = () => {
    router.get(route('lecturer.session.expert', {
        topic_id: props.topicModuleModal.id,
        minuteCounter: props.topicModuleModal.max_time_expert,
        secondCounter: 0,
        transitionMinuteCounter: props.topicModuleModal.transition_time,
        transitionSecondCounter: 0,
    }))
}

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
