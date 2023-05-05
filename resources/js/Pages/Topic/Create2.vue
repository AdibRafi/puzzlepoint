<template>
    <MainLayout>
        <Card title="DEVELOPER">
            {{errors}}
        </Card>
        <div v-if="errors" v-for="error in errors" class="alert alert-error w-96 shadow-lg mb-4">
            <div>
                <font-awesome-icon icon="fa-solid fa-xmark" bounce />
                <p>{{error.valueOf()}}</p>
            </div>
        </div>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step">Modules</li>
            <li class="step">Options</li>
        </ul>
        <form @submit.prevent="form.post(route('topic.store2'))">
            <Card title="Add Topic">
                <InputForm label-name="Topic Name" input-type="text"
                           v-model="form.name"/>
                <InputForm label-name="Number of Modules" input-type="number"
                           v-model="form.no_of_modules"/>
                <div class="tooltip tooltip-right tooltip-primary"
                     data-tip="Advisable to put a bit less time">
                    <InputForm label-name="Maximum Time for Expert Session"
                               input-type="number"
                               v-model="form.max_time_expert"/>
                </div>
                <div class="tooltip tooltip-right tooltip-primary"
                     data-tip="Advisable to put a bit less time">
                    <InputForm label-name="Maximum Time for Jigsaw Session"
                               input-type="number"
                               v-model="form.max_time_jigsaw"/>
                </div>
                <InputForm label-name="Transition Time" input-type="number"
                           v-model="form.transition_time"/>
                <InputForm label-name="Date" input-type="datetime-local"
                           v-model="form.date_time"/>
                <div class="divider"/>
                <template #actions>
                    <button type="submit" :disabled="form.processing"
                            class="btn btn-primary">Save Topic
                    </button>
                </template>
            </Card>

        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {reactive, ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import InputForm from "@/Components/inputForm.vue";

const props = defineProps({
    errors: Object,
})

const form = useForm({
    name: '',
    date_time: '',
    no_of_modules: '',
    max_time_expert: '',
    max_time_jigsaw: '',
    transition_time: '',
})
</script>
