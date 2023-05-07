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
        <form @submit.prevent="form.post(route('module.updateIndex'));">
            <Card title="DEVELOPER">
                <p>name = {{ form.name }}</p>
                <p>learn obj = {{ form.learning_objectives }}</p>
                <p>module = {{ props.moduleModal }}</p>
            </Card>
            <div v-for="(items,index) in props.moduleModal" class="my-4">
                <Card :title="'Module ' + (index+1)">
                    <InputForm label-name="Module Name" v-model="form.name[index]"/>
                    <InputForm label-name="Learning Objectives (Optional)"
                               v-model="form.learning_objectives[index]"/>
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
                            class="btn btn-primary">Proceed
                    </button>
                </template>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import InputForm from "@/Components/InputForm.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topicModal: Object,
    moduleModal: Object
})
const initiateModuleId = [];
const initiateName = {};
const initiateLearningObjective = {};
for (let i = 0; i < props.moduleModal.length; i++) {
    initiateModuleId[i] = props.moduleModal[i].id;
    initiateName[i] = props.moduleModal[i].name;
    initiateLearningObjective[i] = props.moduleModal[i].learning_objectives
}

const form = useForm({
    topic_id: props.topicModal.id,
    module_id: initiateModuleId,
    name: initiateName,
    learning_objectives: initiateLearningObjective,
});

</script>

<style scoped>

</style>
