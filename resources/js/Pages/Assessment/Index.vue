<template>
    <Head title="Assessment"/>
    <MainLayout>
        <Card title="DEVELOPER" class="mb-4">
            <p>Assessment Object</p>
            <p>{{props.assessmentModal}}</p>
        </Card>
        <Card title="Assessment">
            <div class="divider">Question</div>
            <div class="card-actions justify-center">
                <Link :href="route('question.create',{assessment_id:props.assessmentModal.id})"
                      class="btn btn-primary">Create
                </Link>
                <!--todo: create edit/remove for both question and answer-->
                <button class="btn btn-primary">Edit/Delete</button>
            </div>
            <div class="divider">Assessment</div>
            <div class="card-actions justify-center">
                <button class="btn btn-warning">Delete</button>
            </div>
        </Card>
        <Card class="my-4">
            <h2 class="card-title">List of Question</h2>
        </Card>
        <Card :title="quesData.name" v-for="quesData in questionAnswerModal" class="my-4">
            <p>type: {{quesData.type}}</p>
            <div v-for="ansData in quesData.answers">
                <p>{{ansData.name}}</p>
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


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

</script>

<style>

</style>
