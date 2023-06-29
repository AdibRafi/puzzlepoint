<template>
    <!--todo: do for uneven time in student present-->
    <div class="border-2 p-4">
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
            <div/>
            <button @click.prevent="generateTime"
                    class="btn btn-primary float-right ">Generate Time
            </button>
        </div>
    </div>
</template>

<script setup>
import InputText from "@/Components/InputText.vue";
import {reactive, ref} from "vue";

const props = defineProps({
    numberOfModules: Number,
    numberOfStudents: Number,
    // outExpert: Number,
    // outJigsaw: Number,
    // outStudentPresent: Number,
    // outTransition: Number,
})

const inputData = reactive({
    classDuration: 120,
    bufferDuration: 0,
})

const session = reactive({
    expert: null,
    jigsaw: null,
    studentPresent: null,
    transition: null,
})

const displayWholeSession = ref();
const displayBufferSession = ref(0);

const emit = defineEmits(['outData']);
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
    displayBufferSession.value = inputData.bufferDuration;

    displayWholeSession.value = inputData.classDuration;
    displayWholeSession.value -= (session.transition * 2);


    let availableTime = Math.round((displayWholeSession.value - inputData.bufferDuration) / 3);
    session.expert = availableTime;
    session.jigsaw = availableTime * 2;
    session.studentPresent = Math.round((availableTime * 2) / props.numberOfModules);

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
