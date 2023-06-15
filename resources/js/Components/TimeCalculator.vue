<template>
    <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
        <InputText label-title="How long is your class? (in minutes)"
                   input-type="number"
                   v-model="inputData.classDuration"/>
        <InputText label-title="How long is the buffer time? (in minutes)"
                   input-type="number"
                   v-model="inputData.bufferDuration"/>
        <div class="stat w-full border-2">
            <div class="stat-title">Expert Session</div>
            <div class="stat-value">{{ session.expert }} Minutes</div>
            <div class="stat-desc">Duration for the expert session</div>
        </div>
        <div class="stat w-full border-2">
            <div class="stat-title">Jigsaw Session</div>
            <div class="stat-value">{{ session.jigsaw }} Minutes</div>
            <div class="stat-desc">Duration for the jigsaw session</div>
        </div>
        <div class="stat w-full border-2">
            <div class="stat-title">Student present in Jigsaw Session</div>
            <div class="stat-value">{{ session.studentPresent }} Minutes</div>
            <div class="stat-desc">Since there are {{ numberOfModules }} modules, <br/> it will divided with jigsaw
                session time
            </div>
        </div>
        <div class="stat w-full border-2">
            <div class="stat-title">Transition Time</div>
            <div class="stat-value">{{ session.transition }} Minutes</div>
            <div class="stat-desc">Duration before expert and jigsaw session</div>
        </div>
    </div>
    <button @click.prevent="generateTime"
            class="btn btn-primary float-right mt-4">Generate Time
    </button>
</template>

<script setup>
import InputText from "@/Components/InputText.vue";
import {reactive} from "vue";

const props = defineProps({
    numberOfModules: Number,
    numberOfStudents: Number,

})

const inputData = reactive({
    classDuration: 120,
    bufferDuration: null,
})

const session = reactive({
    expert: null,
    jigsaw: null,
    studentPresent: null,
    transition: null,
})

const generateTime = () => {
    if (props.numberOfStudents < 30) {
        session.transition = 2;
    } else if (props.numberOfStudents < 50) {
        session.transition = 3;
    } else if (props.numberOfStudents < 70) {
        session.transition = 4;
    } else if (props.numberOfStudents >= 70) {
        session.transition = 5;
    }
    inputData.classDuration -= session.transition;

    let availableTime = Math.round((inputData.classDuration - inputData.bufferDuration) / 3);
    session.expert = availableTime;
    session.jigsaw = availableTime * 2;
    session.studentPresent = Math.round((availableTime * 2) / props.numberOfModules);
}


</script>
