<template>
    <Head title="Topic"/>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step">Modules</li>
            <li class="step step">Options</li>
            <li class="step step">Verify</li>
        </ul>
        <form @submit.prevent="form.post(route('topic.store'));">
            <div class="card w-96 bg-base-100 shadow-xl my-4">
                <div class="card-body">
                    <h2 class="card-title">Add Topic</h2>
                    <input-form label-name="Topic Name" v-model="form.topicName"/>
                    <span class="block font-medium text-gray-700 text-sm mt-4">Number of Modules</span>
                    <input type="range" min="0" max="100" class="mt-2 range range-primary w-80"
                           step="25" v-model="form.noOfModule"/>
                    <div class="w-full flex justify-between text-xs px-2 w-80">
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                        <span>6</span>
                    </div>
                    <div class="tooltip tooltip-right tooltip-primary" data-tip="Advisable to put a bit less time">
                        <input-form class="my-2" label-name="Maximum Time for Expert Session"
                                    v-model="form.timeExpert"/>
                    </div>
                    <div class="tooltip tooltip-right tooltip-primary" data-tip="Advisable to put a bit less time">
                        <input-form class="my-2" label-name="Maximum Time for Jigsaw Session"
                                    v-model="form.timeJigsaw"/>
                    </div>
                    <input-form class="my-2" label-name="Date" input-type="date" v-model="form.date"/>
                    <div class="card-actions justify-end my-2">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Proceed</button>
                    </div>
                </div>
            </div>
        </form>
    </MainLayout>
</template>

<script setup>
import InputForm from "@/Components/inputForm.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";


const form = useForm({
    'topicName': '',
    'noOfModule': "25",
    'timeExpert': '',
    'timeJigsaw': '',
    'date': '',
})


</script>

<!--archive-->
<!--
<h1 class=" text-xl"> Create Topic</h1>
                <p> contoh -> {{ name }} | {{ description }}</p>
                <ul class="steps w-96">
                    <li class="step step-primary">Topic</li>
                    <li class="step step">Modules</li>
                    <li class="step step">Options</li>
                    <li class="step step">Verify</li>
                </ul>
                <div class="w-7/12 sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <form @submit="formSubmit">
                        <div>
                            <InputForm input-id="topic" input-type="text"
                                       label-for="topic" label-name="Topic Name" v-model="name"/>
                        </div>
                        <div class="mt-4">
                            <span class="block font-medium text-gray-700 text-sm">Number of Modules</span>
                            <input type="range" min="0" max="100" value="25" class="mt-2 range range-primary w-80" step="25"/>
                            <div class="w-full flex justify-between text-xs px-2 w-80">
                                <span>2</span>
                                <span>3</span>
                                <span>4</span>
                                <span>5</span>
                                <span>6</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <InputForm input-id="" input-type="text"
                                       label-for="" label-name="Maximum Time for Expert Session"
                            v-model="description"/>
                        </div>
                        <div class="mt-4">
                            <InputForm input-id="" input-type="text"
                                       label-for="" label-name="Maximum Time for Jigsaw Session" />
                        </div>

                        <div class="mt-6">
                            <SubmitButton button-name="Proceed" button-method="clickTest"/>
                        </div>
                    </form>
                </div>
-->
