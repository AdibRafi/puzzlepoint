<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl">Expert Session</h2>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <div class="flex flex-col items-center">
                    <h2 class="card-title text-2xl">Timer</h2>
                    <div class="grid grid-flow-col gap-5 text-center auto-cols-max my-4">
                        <div class="flex flex-col p-2 bg-primary rounded-box text-neutral-content">
                    <span class="countdown font-mono text-5xl">
                      <span id="minuteElement" style="--value:24;"></span>
                    </span>
                            min
                        </div>
                        <div class="flex flex-col p-2 bg-primary rounded-box text-neutral-content">
                    <span class="countdown font-mono text-5xl">
                      <span id="secondElement" style="--value:45;"></span>
                    </span>
                            sec
                        </div>
                    </div>
                </div>
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
        <div v-for="items in 4" class="m-4">
            <div class="card w-96 bg-base-100 shadow-xl ">
                <div class="card-body">
                    <h2 class="card-title">The Group</h2>
                    <p>This is just a demo group thing</p>
                    <div class="my-4">
                        <p>Student 1</p>
                        <p>Student 2</p>
                        <p>Student 3</p>
                        <p>Student 4</p>
                    </div>
                </div>
            </div>
            <!--    <div>-->
            <!--        <embed src="public/pdf/testPDF.pdf" width="800" height="900">-->
            <!--    </div>-->
        </div>
    </MainLayout>
</template>

<script setup>
import {onMounted} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";

//todo: assign the time with a DB in topic
let minuteCounter = 24;
let secondCounter = 5;
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

// function countDownTimer() {
//     if (counter > 0) {
//         setTimeout(() => {
//             counter -= 1;
//             countDownTimer()
//         }, 1000);
//         counter--;
//     } else {
//         counter = 10;
//     }
//     // document.getElementById('counterElement').style.setProperty('--value', counter
//     // )
// }
//
// onMounted(() => {
//     countDownTimer()
// });

</script>

<style scoped>

</style>
