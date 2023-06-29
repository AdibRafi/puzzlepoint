<template>
    <div class="border-2 p-4 overflow-hidden">
        <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
            <InputText label-title="How long is your class? (in minutes)"
                       input-type="number"
                       v-model="inputData.classDuration"/>
            <InputText label-title="How long is the buffer time? (in minutes)"
                       input-type="number"
                       v-model="inputData.bufferDuration"/>
            <div class="stat w-full border-2">
                <div class="stat-title">Jigsaw Learning Session</div>
                <div class="stat-value">{{ inputData.classDuration }} Minutes</div>
                <div class="stat-desc">Duration for the whole session</div>
            </div>
            <div class="stat w-full border-2">
                <div class="stat-title">Buffer Time</div>
                <div class="stat-value">{{ displayBufferSession }} Minutes</div>
                <div class="stat-desc">Duration available before starting the session</div>
            </div>
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
                <div class="stat-title">Transition Time</div>
                <div class="stat-value">{{ session.transition }} Minutes</div>
                <div class="stat-desc">Duration before expert and jigsaw session</div>
            </div>
        </div>
        <div class="divider">Duration for Student to Present in Jigsaw Session</div>
        <div v-if="timeMethod==='even'">
            <div class="stat w-full border-2">
                <div class="stat-title">Duration</div>
                <div class="stat-value">{{ session.studentPresent[0] }} Minutes</div>
                <div class="stat-desc">Each student will equally present with a given time</div>
            </div>
        </div>
        <div v-else-if="timeMethod==='uneven'">
            <GridLayout>
                <div v-for="(moduleData,index) in props.modulesData" :key="moduleData">
                    <Stat :title="moduleData.name">
                        <div class="stat-value">{{ session.studentPresent[index] }} Minutes</div>
                        <select class="select select-bordered mt-2"
                                v-model="unevenTime[index]">
                            <option :value="null" disabled selected>Pick One</option>
                            <option :value="1">Short</option>
                            <option :value="2">Medium</option>
                            <option :value="3">Long</option>
                        </select>
                    </Stat>
                </div>
            </GridLayout>
        </div>
        <div class="divider"/>
        <button @click.prevent="generateTime"
                class="btn btn-primary float-right">Generate Time
        </button>
    </div>
</template>

<script setup>
import InputText from "@/Components/InputText.vue";
import {reactive, ref} from "vue";
import GridLayout from "@/Components/GridLayout.vue";
import Stat from "@/Components/Stat.vue";

const props = defineProps({
    numberOfModules: Number,
    numberOfStudents: Number,
    timeMethod: String,
    modulesData: Object,
})

const inputData = reactive({
    classDuration: 120,
    bufferDuration: 0,
})

const session = reactive({
    expert: null,
    jigsaw: null,
    studentPresent: [],
    transition: null,
})

const displayWholeSession = ref();
const displayBufferSession = ref(0);

const unevenTime = ref([]);
const displayUnevenTime = ref([]);

const emit = defineEmits(['outData']);

const generateTime = () => {
    if (props.timeMethod === 'uneven' &&
        unevenTime.value.length !== props.modulesData.length) {
        return null;
    }
    if (props.numberOfStudents < 30) {
        session.transition = 2;
    } else if (props.numberOfStudents < 50) {
        session.transition = 3;
    } else if (props.numberOfStudents < 70) {
        session.transition = 4;
    } else if (props.numberOfStudents >= 70) {
        session.transition = 5;
    }
    displayBufferSession.value = inputData.bufferDuration;

    displayWholeSession.value = inputData.classDuration;
    displayWholeSession.value -= (session.transition * 2);

    session.studentPresent = [];

    let availableTime = Math.round((displayWholeSession.value - inputData.bufferDuration) / 3);
    session.expert = availableTime;
    session.jigsaw = availableTime * 2;

    if (props.timeMethod === 'uneven') {
        const sum = unevenTime.value.reduce((a, b) => a + b, 0)
        const singleDuration = Math.round(session.jigsaw / sum)
        // const singleDuration = session.jigsaw / sum
        // displayUnevenTime.value = [];
        for (let i = 0; i < props.modulesData.length; i++) {
            session.studentPresent.push(singleDuration * unevenTime.value[i]);
            // displayUnevenTime.value.push(singleDuration * unevenTime.value[i]);
        }
    } else {
        for (let i = 0; i < props.modulesData.length; i++) {
            session.studentPresent.push(Math.round((availableTime * 2) / props.numberOfModules));
        }
    }

    emit('outData', {
        outExpert: session.expert,
        outJigsaw: session.jigsaw,
        outStudentPresent: session.studentPresent,
        outTransition: session.transition,
        outSession: inputData.classDuration,
        outBuffer: inputData.bufferDuration,
    });
}


</script>
