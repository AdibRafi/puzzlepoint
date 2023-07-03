<template>
    <SessionLayout page-title="Jigsaw Session">
        <TitleCard :title="'Jigsaw Session - ' + props.topicModal.name">
            <TimerDisplayStatic session-type="Jigsaw Time"
                                :minute-counter="minuteCounter"
                                :second-counter="secondCounter"
                                :transition-minute-counter="transitionMinuteCounter"
                                :transition-second-counter="transitionSecondCounter"
                                :module-minute-counter="moduleMinuteCounter"
                                :module-second-counter="moduleSecondCounter"
                                :module-number="moduleNum"/>
            <CardTable :title="props.groupUserModal.name"
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
                        <tr v-for="userData in props.groupUserModal.users" :key="userData">
                            <td>{{ userData.name }}</td>
                            <td>{{ userData.pivot.module_name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </CardTable>
            <div class="divider">Module File</div>
            <div v-for="moduleData in props.moduleModal" :key="moduleData"
                 class="my-2">
                <Stat :title="moduleData.name">
                    <iframe v-if="moduleData.file_path"
                            class="mt-4"
                            :src="'../../../../../' + moduleData.file_path"
                            width="100%" height="800"/>
                </Stat>
            </div>
        </TitleCard>
    </SessionLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import {onMounted, ref} from "vue";
import '../../../bootstrap'
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";
import {router} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import SessionLayout from "@/Layouts/SessionLayout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import CardTable from "@/Components/CardTable.vue";
import Stat from "@/Components/Stat.vue";


const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);
const transitionSecondCounter = ref(0);
const moduleMinuteCounter = ref(0);
const moduleSecondCounter = ref(0);
const moduleNum = ref();


onMounted(() => {
    console.log('mounted')
})

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,

});
window.Echo.channel('update-jigsaw-session')
    .listen('UpdateJigsawSession', (e) => {
        console.log(e);
        minuteCounter.value = e.minuteCounter;
        secondCounter.value = e.secondCounter;
        transitionMinuteCounter.value = e.transitionMinuteCounter;
        transitionSecondCounter.value = e.transitionSecondCounter;
        moduleMinuteCounter.value = e.moduleMinuteCounter;
        moduleSecondCounter.value = e.moduleSecondCounter;
        moduleNum.value = e.moduleNumber;
    });

window.Echo.channel('end-session-channel')
    .listen('EndSession', (e) => {
        router.get(route('student.session.end'), {topic_id: props.topicModal.id})
    })
</script>
