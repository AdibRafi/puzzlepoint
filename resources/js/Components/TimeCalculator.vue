<template>
    <div class="border-2 p-4 overflow-hidden">
        <GridLayout v-if="!isManual">
            <InputText label-title="How long is your class? (in minutes)"
                       input-type="number"
                       v-model="inputData.classDuration"/>
            <InputText label-title="How long is the buffer time? (in minutes)"
                       input-type="number"
                       v-model="inputData.bufferDuration"/>
        </GridLayout>
        <div v-else-if="isManual">
            <GridLayout>
                <InputText label-title="How long is your class? (in minutes)"
                           input-type="number"
                           v-model="inputData.classDuration"/>
                <InputText label-title="How long is the buffer time? (in minutes)"
                           input-type="number"
                           v-model="inputData.bufferDuration"/>
                <InputText label-title="How long is the Expert Session? (in minutes)"
                           input-type="number"
                           v-model="session.expert"/>
                <InputText label-title="How long is the Jigsaw Session? (in minutes)"
                           input-type="number"
                           v-model="session.jigsaw"/>
                <div class="form-control w-full max-w-xs">
                    <label class="label">
                        <span class="label-text">Transition Time</span>
                    </label>
                    <select class="select select-bordered"
                            v-model="session.transition">
                        <option :value="null" disabled selected>Pick Transition Time</option>
                        <option :value="2">2 Minutes</option>
                        <option :value="3">3 Minutes</option>
                        <option :value="4">4 Minutes</option>
                        <option :value="5">5 Minutes</option>
                    </select>
                </div>
            </GridLayout>
            <div class="divider"/>
            <GridLayout>
                <div v-for="(moduleData,index) in props.modulesData" :key="moduleData">
                    <Stat :title="moduleData.name">
                        <InputText label-title="How long? (in minutes)"
                        input-type="number" v-model="session.studentPresent[index]"/>
                    </Stat>
                </div>
            </GridLayout>
        </div>
        <div class="divider"/>
        <GridLayout>
            <Stat title="Jigsaw Learning Session" desc="Duration for the whole session">
                <div class="stat-value">{{ inputData.classDuration }} Minutes</div>
            </Stat>
            <Stat title="Buffer Time" desc="Duration available before starting the session">
                <div class="stat-value">{{ displayBufferSession }} Minutes</div>
            </Stat>
            <Stat title="Expert Session" desc="Duration for the Expert Session">
                <div class="stat-value">{{ displayExpertSession }} Minutes</div>
            </Stat>
            <Stat title="Jigsaw Session" desc="Duration for the Jigsaw Session">
                <div class="stat-value">{{ displayJigsawSession }} Minutes</div>
            </Stat>
            <Stat title="Transition Time" desc="Duration before expert and jigsaw session">
                <div class="stat-value">{{ displayTransitionTime }} Minutes</div>
            </Stat>
        </GridLayout>
        <div class="divider">Duration for Student to Present in Jigsaw Session</div>
        <div v-if="isManual">
            <GridLayout>
                <div v-for="(moduleData,index) in props.modulesData" :key="moduleData">
                    <Stat :title="moduleData.name">
                        <div class="stat-value">{{displayStudentPresent[index]}} Minutes</div>
                    </Stat>
                </div>
            </GridLayout>
        </div>
        <div v-if="timeMethod==='even'">
            <Stat v-if="!isManual"
                  title="Duration" desc="Each Student will Equally Present with a Given Time">
                <div class="stat-value">{{ displayStudentPresent[0] }} Minutes</div>
            </Stat>
        </div>
        <div v-else-if="timeMethod==='uneven'">
            <GridLayout v-if="!isManual">
                <div v-for="(moduleData,index) in props.modulesData" :key="moduleData">
                    <Stat :title="moduleData.name">
                        <div class="stat-value">{{ displayStudentPresent[index] }} Minutes</div>
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
        <div v-if="!isManual">
            <button @click.prevent="changeInput"
                    class="btn btn-primary">
                Change to Manual Input
            </button>
            <button @click.prevent="generateTime"
                    class="btn btn-primary float-right">Generate Time
            </button>

        </div>
        <div v-else-if="isManual">
            <button @click.prevent="changeInput"
                    class="btn btn-primary">
                Change to System Time Calculator
            </button>
            <button @click.prevent="saveTime"
                    class="btn btn-primary float-right">
                Save Time
            </button>
        </div>
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

const displayWholeSession = ref(0);
const displayBufferSession = ref(0);
const displayExpertSession = ref(0);
const displayJigsawSession = ref(0);
const displayTransitionTime = ref(0);
const displayStudentPresent = ref([]);

const unevenTime = ref([]);
const isManual = ref(false);

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

    displayExpertSession.value = session.expert;
    displayJigsawSession.value = session.jigsaw;
    displayTransitionTime.value = session.transition;
    displayStudentPresent.value = session.studentPresent;

    emit('outData', {
        outExpert: displayExpertSession.value,
        outJigsaw: displayJigsawSession.value,
        outStudentPresent: displayStudentPresent.value,
        outTransition: displayTransitionTime,
        outSession: inputData.classDuration,
        outBuffer: displayBufferSession,
        outTimeMethod: props.timeMethod
    });
}

const changeInput = () => {
    isManual.value = !isManual.value;

    inputData.classDuration = 120;
    inputData.bufferDuration = 0;

    session.expert = null;
    session.jigsaw = null;
    session.studentPresent = [];
    session.transition = null;

    if (isManual) {
        session.studentPresent.push(ref(Number))
    }

    displayWholeSession.value = ref();
    displayBufferSession.value = 0;
    displayExpertSession.value = 0;
    displayJigsawSession.value = 0;
    displayTransitionTime.value = 0;
    displayStudentPresent.value = [];
}

const saveTime = () => {
    displayBufferSession.value = inputData.bufferDuration;
    displayExpertSession.value = session.expert;
    displayJigsawSession.value = session.jigsaw;
    displayTransitionTime.value = session.transition;
    displayStudentPresent.value = session.studentPresent;

    emit('outData', {
        outExpert: displayExpertSession.value,
        outJigsaw: displayJigsawSession.value,
        outStudentPresent: displayStudentPresent.value,
        outTransition: displayTransitionTime,
        outSession: inputData.classDuration,
        outBuffer: displayBufferSession,
        outTimeMethod: 'manual'
    });
}

</script>
