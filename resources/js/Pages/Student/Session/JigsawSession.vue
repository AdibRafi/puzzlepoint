<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl">Jigsaw Session</h2>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <div class="flex flex-col items-center">
                    <h2 class="card-title text-2xl">Timer</h2>
                    <div class="grid grid-flow-col gap-5 text-center auto-cols-max my-4">
                        <div class="flex flex-col p-2 bg-primary rounded-box text-neutral-content">
                            <span class="countdown font-mono text-5xl">
                              <span id="minuteElement"
                                    :style="'--value:' + props.topicModal.max_time_jigsaw + ';'"></span>
                            </span>
                            min
                        </div>
                        <div class="flex flex-col p-2 bg-primary rounded-box text-neutral-content">
                            <span class="countdown font-mono text-5xl">
                              <span id="secondElement" style="--value:0;"></span>
                            </span>
                            sec
                        </div>
                    </div>
                </div>
                <!--                <div class="card-actions flex justify-end">-->
                <!--                    <div>-->
                <!--                        <select class="select select-primary w-full max-w-xs my-2">-->
                <!--                            <option disabled selected>Select Minute</option>-->
                <!--                            <option>1 minute</option>-->
                <!--                            <option>2 minute</option>-->
                <!--                            <option>3 minute</option>-->
                <!--                            <option>4 minute</option>-->
                <!--                            <option>5 minute</option>-->
                <!--                        </select>-->
                <!--                        <button class="btn btn-primary">Add Time</button>-->
                <!--                    </div>-->
                <!--                    <div>-->
                <!--                        <button class="btn btn-primary">End Early</button>-->
                <!--                    </div>-->
                <!--                </div>-->
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <h2 class="card-title">Your Group</h2>
                <div class="my-4">
                    <div v-for="userData in props.userModal">
                        <p>{{ userData.name }}</p>
                    </div>
                    <p class="my-10">{{ props.groupModal }}</p>
                    <!--                    <p>{{props.userModal}}</p>-->
                </div>
            </div>
        </div>
        <!--    <div>-->
        <!--        <embed src="public/pdf/testPDF.pdf" width="800" height="900">-->
        <!--    </div>-->
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    topicModal: Object,
    groupModal: Object,
    userModal: Object,
})

let minuteCounter = props.topicModal.max_time_jigsaw;
let secondCounter = 0;


setInterval(() => {
    if (secondCounter > 0) {
        secondCounter--;
    } else {
        secondCounter = 59;
        minuteCounter--;
    }
    document.getElementById('secondElement').style.setProperty('--value', secondCounter);
    document.getElementById('minuteElement').style.setProperty('--value', minuteCounter);
}, 1000)
</script>

<style scoped>

</style>
