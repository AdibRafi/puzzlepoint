<template>
    <div class="card w-96 bg-base-100 shadow-xl my-4">
        <div class="card-body">
            <div class="flex flex-col items-center">
                <h2 class="card-title text-2xl">Timer</h2>
                <div class="grid grid-flow-col gap-5 text-center auto-cols-max my-4">
                    <div class="flex flex-col p-2 bg-primary rounded-box text-neutral-content">
                            <span class="countdown font-mono text-5xl">
                              <span id="minuteElement"
                                    :style="'--value:' + props.minuteTime + ';'"></span>
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
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    minuteTime: String,

});
let minuteCounter = props.minuteTime;
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
