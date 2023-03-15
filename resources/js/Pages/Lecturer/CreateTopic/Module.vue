<template>
    <Head title="Module"/>
    <MainLayout>
        <!--                <h1 class="text-xl font-bold">MODULE</h1>-->
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step">Options</li>
            <li class="step step">Verify</li>
        </ul>
        <form @submit.prevent="form.post(route('module.store'));">
            <div v-for="items in no_of_modules" class="m-4">
                <div class="card w-96 bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h1 class="font-bold">Module {{ items }}</h1>
                        <p>{{ form.name }}</p>
                        <p>{{ form.learning_objectives }}</p>
                        <InputForm label-name="Module Name" v-model="form.name[items]"/>
                        <InputForm label-name="Learning Objectives (Optional)"
                                   v-model="form.learning_objectives[items]"/>

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

const no_of_modules = parseInt(props.topicData.no_of_modules);


const props = defineProps({
    topicData: Object,
    topicModal: Object,
})
//todo: file upload?????


const form = useForm({
    no_of_modules: no_of_modules,
    topic_id: props.topicModal.id,
    name: {},
    learning_objectives: {
        1: '',
        2: '',
        3: '',
        4: '',
        5: '',
        6: '',
    },
});

</script>

<style scoped>

</style>
