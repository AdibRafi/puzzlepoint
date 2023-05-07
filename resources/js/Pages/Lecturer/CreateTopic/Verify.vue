<template>
    <MainLayout>
        <!--todo:Redo-->
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
            <li class="step step-primary">Verify</li>
        </ul>
        <div class="card w-96 bg-base-100 shadow-xl m-4">
            <div class="card-body">
                <h1 class="card-title">Time Overview</h1>
                <h1>Expert Session = 30 minutes</h1>
                <h1>Jigsaw Session = 60 minutes</h1>
                <div class="form-control w-full max-w-xs">
                    <label class="label">
                        <span class="label-text">Transition Time</span>
                    </label>
                    <select class="select select-primary select-bordered">
                        <option disabled selected>Pick one</option>
                        <option>2 Minutes</option>
                        <option>3 Minutes</option>
                        <option>4 Minutes</option>
                        <option>5 Minutes</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl m-4">
            <div class="card-body">
                <h1 class="card-title">Group Overview: Expert Group</h1>
            </div>
        </div>
        <div v-for="(data,i) in dataForExpert" :key="data" class="m-4">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h1 class="card-title">Module {{ i + 1 }}</h1>
                    <h1>Name: {{ data.ModuleName }}</h1>
                    <div class="divider"></div>
                    <div>
                        <p>{{ data.s1 }}</p>
                        <p>{{ data.s2 }}</p>
                        <p>{{ data.s3 }}</p>
                        <p>{{ data.s4 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl m-4">
            <div class="card-body">
                <h1 class="card-title">Group Overview: Jigsaw Group</h1>
            </div>
        </div>
        <div v-for="(data,i) in dataForJigsaw" :key="data" class="m-4">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h1 class="card-title">Jigsaw {{ i + 1 }}</h1>
                    <div class="divider"></div>
                    <div>
                        <p>{{ data.s1 }}</p>
                        <p>{{ data.s2 }}</p>
                        <p>{{ data.s3 }}</p>
                        <p>{{ data.s4 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="form.post(route('topic.storeVerify'))">
            <div class="card w-96 bg-base-100 shadow-xl my-2 mb-4">
                <div class="card-body">
                    <div class="card-actions justify-center my-2">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Publish</button>
                    </div>
                </div>
            </div>
        </form>
    </MainLayout>
</template>

<script setup>
import {ref} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import InputForm from "@/Components/InputForm.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    topicModal: Object,
})

const form = useForm({
    topic_id: props.topicModal.id,
})

const names = ref(['test1', 'test2', 'test3', 'test4']);
const dataForExpert = [{
    "ModuleName": "mt1",
    "s1": "s11",
    "s2": "s12",
    "s3": "s13",
    "s4": "s14",
}, {
    "ModuleName": "mt2",
    "s1": "s21",
    "s2": "s22",
    "s3": "s23",
    "s4": "s24",
}, {
    "ModuleName": "mt3",
    "s1": "s31",
    "s2": "s32",
    "s3": "s33",
    "s4": "s34",
}, {
    "ModuleName": "mt4",
    "s1": "s41",
    "s2": "s42",
    "s3": "s43",
    "s4": "s44",
},
]
const dataForJigsaw = [{
    "ModuleName": "mt1",
    "s1": "s11",
    "s2": "s21",
    "s3": "s31",
    "s4": "s41",
}, {
    "ModuleName": "mt2",
    "s1": "s12",
    "s2": "s22",
    "s3": "s32",
    "s4": "s42",
}, {
    "ModuleName": "mt3",
    "s1": "s13",
    "s2": "s23",
    "s3": "s33",
    "s4": "s43",
}, {
    "ModuleName": "mt4",
    "s1": "s14",
    "s2": "s24",
    "s3": "s34",
    "s4": "s44",
},
]

</script>

<style scoped>

</style>
