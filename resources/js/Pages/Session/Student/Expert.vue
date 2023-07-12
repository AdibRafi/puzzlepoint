<template>
    <SessionLayout page-title="Expert Session">
        <TitleCard :title="'Expert Session - ' + props.topicModal.name">
            <TimerDisplayStatic session-type="Expert Time"
                                :minute-counter="minuteCounter"
                                :second-counter="secondCounter"
                                :transition-minute-counter="transitionMinuteCounter"
                                :transition-second-counter="transitionSecondCounter"/>
            <GridLayout>
                <Stat title="Module Name" :value="props.moduleModal.name"
                      desc="Read Through and Discuss with your Team Members"/>
                <CardTable :title="props.groupUserModal.name">
                    <div class="overflow-x-auto">
                        <table class="table w-full table-compact">
                            <thead>
                            <tr>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="userData in props.groupUserModal.users" :key="userData">
                                <td>{{ userData.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </CardTable>
            </GridLayout>
            <div class="divider"/>
            <iframe v-if="props.moduleModal.file_path"
                    :src="'../../../../../' + props.moduleModal.file_path"
                    width="100%" height="800"/>
        </TitleCard>
    </SessionLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../../bootstrap'
import {router} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import TimerDisplayStatic from "@/Components/TimerDisplayStatic.vue";
import VuePdfEmbed from "vue-pdf-embed";
import Layout from "@/Layouts/Layout.vue";
import SessionLayout from "@/Layouts/SessionLayout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import GridLayout from "@/Components/GridLayout.vue";
import Stat from "@/Components/Stat.vue";
import CardTable from "@/Components/CardTable.vue";

const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
    moduleModal: Object,
})

console.log(props.moduleModal.file_path);

const minuteCounter = ref(0);
const secondCounter = ref(0);
const transitionMinuteCounter = ref(0);
const transitionSecondCounter = ref(0);

window.Echo.channel('move-expert-channel')
    .stopListening('MoveExpertSession');
window.Echo.channel('move-jigsaw-channel')
    .stopListening('MoveJigsawSession')

window.Echo.channel('move-jigsaw-channel')
    .listen('MoveJigsawSession', (e) => {
        router.get(route('student.session.jigsaw', {topic_id: props.topicModal.id}))
    });

const listenChannel = () => {
    window.Echo.channel('update-expert-session')
        .listen('UpdateExpertSession', (e) => {
            console.log(e);
            minuteCounter.value = e.minuteCounter;
            secondCounter.value = e.secondCounter;
            transitionMinuteCounter.value = e.transitionMinuteCounter;
            transitionSecondCounter.value = e.transitionSecondCounter;
        });

}

listenChannel()

</script>
