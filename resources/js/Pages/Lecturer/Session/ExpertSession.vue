<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl">Expert Session</h2>
                <Link :href="route('pusher.test')" class="btn btn-accent">PUSH</Link>
            </div>
        </div>
        <TimerDisplay :minute-time="props.topicModal.max_time_expert"/>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <div class="card-actions flex justify-end">
                    <div>
                        <select class="select select-primary w-full max-w-xs my-2">
                            <option disabled selected>Select Minute</option>
                            <option>1 minute</option>
                            <option>2 minute</option>
                            <option>3 minute</option>
                            <option>4 minute</option>
                            <option>5 minute</option>
                        </select>
                        <button class="btn btn-primary">Add Time</button>
                    </div>
                    <div>
                        <button class="btn btn-primary">End Early</button>
                    </div>
                </div>
            </div>
        </div>
        <LecturerDisplayGroup :group-user-modal="props.groupUserModal"/>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TimerDisplay from "@/Components/timerDisplay.vue";
import LecturerDisplayGroup from "@/Components/lecturerDisplayGroup.vue";
import '../../../bootstrap';
import {Link} from "@inertiajs/vue3";
// import {onMounted} from "vue";


const props = defineProps({
    topicModal: Object,
    groupUserModal: Object,
})


// window.Echo.channel("attendance-expert-channel").listen('.event', (e) => {
//     console.log('wow dpt');
// })

</script>

<style scoped>

</style>
