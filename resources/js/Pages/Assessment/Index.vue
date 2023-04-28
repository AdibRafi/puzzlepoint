<template>
    <Head title="Assessment"/>
    <MainLayout>
        <Card title="DEVELOPER" class="mb-4">
            <p>Assessment Object</p>
            <p>{{ props.assessmentModal }}</p>
        </Card>
        <Card title="Assessment">
            <div class="card-actions justify-center my-2">
                <Link :href="route('question.create',{assessment_id:props.assessmentModal.id})"
                      class="btn btn-primary">Create Question
                </Link>
            </div>
            <div class="card-actions justify-center">
                <button class="btn btn-warning">Delete Assessment</button>
            </div>
            <div class="divider"/>
            <form @submit.prevent="form.post(route('assessment.publish'))">
                <InputForm label-name="Time" input-placeholder="in minutes" class="mb-4" v-model="form.time"/>
                <button type="submit" :disabled="form.processing" class="btn btn-accent">
                    Publish
                </button>
            </form>
        </Card>
        <Card class="my-4">
            <h2 class="card-title">List of Question</h2>
        </Card>
        <Card :title="quesData.name" v-for="quesData in questionAnswerModal" class="my-4">
            <p>type: {{ quesData.type }}</p>
            <div v-for="ansData in quesData.answers">
                <p>{{ ansData.name }}</p>
            </div>
            <template #actions>
                <Link :href="route('question.edit',quesData)" class="btn btn-accent">Edit question</Link>
            </template>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import InputForm from "@/Components/inputForm.vue";


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

const form = useForm({
    topic_id: props.topicModal.id,
    time: '',
})
</script>

<style>

</style>
