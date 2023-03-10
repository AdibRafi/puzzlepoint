<template>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
            <li class="step step">Verify</li>
        </ul>
        <div class="card w-96 bg-base-100 shadow-xl my-2">
            <div class="card-body">
                <h1 class="card-title">Grouping Options</h1>
                <p>Choose your options</p>
                <div class="divider"></div>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="radio checked:radio-primary mx-2"
                                   checked/>
                            <span class="label-text">No Preferences</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="radio checked:radio-primary mx-2"/>
                            <span class="label-text">Gender</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-2">
            <div class="card-body">
                <h1 class="card-title">Time Options</h1>
                <p>Jigsaw Session = 60 Minutes</p>
                <p>How do you want to divide presentation time?</p>
                <div class="divider"></div>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-11" class="radio checked:radio-primary mx-2" checked
                                   value="A" v-model="selected"/>
                            <span class="label-text">Even (15 min)</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-11" class="radio checked:radio-primary mx-2"
                                   value="B" v-model="selected"/>
                            <span class="label-text">Uneven</span>
                        </label>
                    </div>
                    <div v-show="selected === 'B'">
                        <p>Type in number (represent %)</p>
<!--                        <input-form label-name="Module 1"></input-form>-->
<!--                        <input-form label-name="Module 2"></input-form>-->
<!--                        <input-form label-name="Module 3"></input-form>-->
<!--                        <input-form label-name="Module 4"></input-form>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-2 mb-4">
            <div class="card-body">
                <div class="card-actions justify-center my-2">
                    <button class="btn btn-primary">proceed</button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import {ref} from "vue";
// import InputForm from "../reusable/inputForm.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const selected = ref();
</script>

<style scoped>

</style>
