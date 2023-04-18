<template>
    <Head title="Module"/>
    <MainLayout>
        <!--                <h1 class="text-xl font-bold">MODULE</h1>-->
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step">Options</li>
            <li class="step">Verify</li>
        </ul>
        <form @submit.prevent="form.post(route('module.store'));">
            <Card title="DEVELOPER">
                <p>name = {{ form.name }}</p>
                <p>learn obj = {{ form.learning_objectives }}</p>
            </Card>
            <div v-for="items in no_of_modules" class="my-4">
                <Card :title="'Module ' + items">
                    <InputForm label-name="Module Name" v-model="form.name[items]"/>
                    <InputForm label-name="Learning Objectives (Optional"
                               v-model="form.learning_objectives[items]"/>
                    <template #actions>
                        <input type="file"
                               class="file-input file-input-bordered
                                      file-input-primary mt-4"/>
                    </template>
                </Card>
            </div>
            <Card title="Make sure to check!">
                <template #actions>
                    <button type="submit" :disabled="form.processing"
                            class="btn btn-primary">Proceed</button>
                </template>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import InputForm from "@/Components/inputForm.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topicData: Object,
    topicModal: Object,
})


const no_of_modules = parseInt(props.topicModal.no_of_modules);
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
