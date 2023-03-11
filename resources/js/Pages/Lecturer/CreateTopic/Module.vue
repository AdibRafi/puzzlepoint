<template>
    <MainLayout>
        <!--                <h1 class="text-xl font-bold">MODULE</h1>-->
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step">Options</li>
            <li class="step step">Verify</li>
        </ul>
        <div v-for="items in no_of_modules" class="m-4">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h1 class="font-bold">Module {{ items }}</h1>
                    <InputForm label-name="Module Name"/>
                    <InputForm label-name="Learning Objectives (Optional)"/>
                    <div class="card-actions justify-end mt-5">
                        <input type="file"
                               class="file-input file-input-bordered file-input-primary w-full max-w-xs">
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl m-4">
            <div class="card-body">
                <h2 class="card-title">Make sure to check!</h2>
                <div class="card-actions justify-end">
                    <submit-button button-name="Proceed"/>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import InputForm from "@/Components/inputForm.vue";
import SubmitButton from "@/Components/submitButton.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    topicData: Object,
})

const no_of_modules = parseInt(props.topicData.no_of_modules);

</script>

<style scoped>

</style>
