<template>
    <Head title="Module"/>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step">Options</li>
        </ul>
        <div v-if="errors" v-for="error in errors" class="alert alert-error w-96 shadow-lg mb-4">
            <div>
                <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                <p>{{ error.valueOf() }}</p>
            </div>
        </div>
        <Card title="DEVELOPER">
            <p>{{ form.modules }}</p>
        </Card>
        <form @submit.prevent="form.post(route('module.store'));">
            <div v-for="(module,index) in modules" class="my-4">
                <Card :title="'Module ' + (index +1)">
                    <InputForm label-name="Module Name" v-model="module.name"/>
                    <InputForm label-name="Learning Objectives (Optional)"
                               v-model="module.learning_objectives"/>
                    <template #actions>
                        <input type="file"
                               class="file-input file-input-bordered
                                      file-input-primary mt-4"
                               @input="module.file_path = $event.target.files[0]"/>
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
import {reactive} from "vue";

const props = defineProps({
    topicModal: Object,
    errors: Object,

})

const modules = reactive([]);
for (let i = 0; i < props.topicModal.no_of_modules; i++) {
    modules.push({
        name: '',
        learning_objectives: '',
        file_path: ''
    })
}

const form = useForm({
    topic_id: props.topicModal.id,
    modules: modules,
});

</script>

<style scoped>

</style>
