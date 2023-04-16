<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl">Expert Session</h2>
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
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <h2 class="card-title">Absent Student</h2>
                <div v-for="data in props.absentStudentModal">
                    <p class="text-red-500">{{data.name}}</p>
                </div>
                <p class="text-red-500"></p>
            </div>
        </div>
        <LecturerDisplayGroup :group-user-attend-modal="props.groupUserAttendModal"/>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TimerDisplay from "@/Components/timerDisplay.vue";
import LecturerDisplayGroup from "@/Components/lecturerDisplayGroup.vue";
import '../../../bootstrap';
import {Link, router, useForm} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
// import {onMounted} from "vue";

const datas = ref([]);

const props = defineProps({
    topicModal: Object,
    groupUserAttendModal: Object,
    absentStudentModal: Object,
})

onMounted(() => {
    console.log('mounted');
    // axios.get("/fetchAbsentStudentE").then((response)=>{
    //     datas.value = response.data;
    // })
})

window.Echo.channel("attendance-expert-channel")
    .listen('AttendanceExpert', (e) => {
        console.log(e);
        router.reload();
        // axios.get("/fetchAbsentStudentE").then((response) => {
        //     datas.value = response.data;
        // })
    })

</script>

<style scoped>

</style>
