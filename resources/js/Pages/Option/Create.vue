<template>
    <Head title="Option"/>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
        </ul>
        <form @submit.prevent="form.post(route('option.store'))" novalidate>
            <Card title="Grouping Options" class="my-4">
                <p>Choose your options</p>
                <div class="divider"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" value="none" class="radio checked:radio-primary mx-2"
                                   v-model="form.groupMethod"
                                   checked/>
                            <span class="label-text">No Preferences</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" value="gender" class="radio checked:radio-primary mx-2"
                                   v-model="form.groupMethod"/>
                            <span class="label-text">Gender</span>
                        </label>
                    </div>
                </div>
            </Card>
            <Card title="Time Options" class="my-4">
                <p>Jigsaw Session = {{ props.topicModal.max_time_jigsaw }} minutes</p>
                <p>How do you want to divide presentation time?</p>
                <div class="divider"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" class="radio checked:radio-primary mx-2"
                                   value="even" v-model="form.timeMethod" checked
                                   @click="evenTimeFunction"/>
                            <span class="label-text">Even ({{ evenTime }} min)</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" class="radio checked:radio-primary mx-2"
                                   value="uneven" v-model="form.timeMethod"
                                   @click="unevenTime"/>
                            <span class="label-text">Uneven</span>
                        </label>
                    </div>
                    <div v-show="form.timeMethod === 'uneven'">
                        <p>How Many Minutes?</p>
                        <div v-for="item in props.topicModal.no_of_modules">
                            <InputForm :label-name="'Module ' + item"
                                       input-type="number"
                                       @input="inputChange"
                                       v-model="form.tm[item]"/>
                        </div>
                        <p class="mt-4">Total minute = {{ totalTime }}</p>
                    </div>
                </div>
            </Card>
            <Card>
                <button type="submit"
                        :disabled="form.processing"
                        class="btn btn-primary">Add Option
                </button>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import InputForm from "@/Components/InputForm.vue";
import {Head, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import {onMounted, onUpdated, reactive, ref} from "vue";

const props = defineProps({
    topicModal: Object,
})

const evenTime = props.topicModal.max_time_jigsaw / parseInt(props.topicModal.no_of_modules)
const totalTime = ref(0);
const tm = reactive({
    1: 0,
    2: 0,
    3: 0,
    4: 0,
    5: 0,
    6: 0,
})

const form = useForm({
    topic_id: props.topicModal.id,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: tm,
})

const unevenTime = () => {
    tm[1] = 0
    tm[2] = 0
    tm[3] = 0
    tm[4] = 0
    tm[5] = 0
    tm[6] = 0
}

const evenTimeFunction = () => {
    tm[1] = evenTime
    tm[2] = evenTime
    tm[3] = evenTime
    tm[4] = evenTime
    tm[5] = evenTime
    tm[6] = evenTime
}

onMounted(()=>{
    evenTimeFunction();
})

const inputChange = () => {
    totalTime.value = parseInt(tm[1]) + parseInt(tm[2]) +
        parseInt(tm[3]) + parseInt(tm[4]) +
        parseInt(tm[5]) + parseInt(tm[6]);
}

</script>

<style scoped>

</style>
