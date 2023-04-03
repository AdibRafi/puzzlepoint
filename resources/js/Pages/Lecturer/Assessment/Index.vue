<template>
    <Head title="Assessment"/>
    <MainLayout>
        <div class="card w-96 bg-base-100 pb-5 shadow-xl my-2">
            <div class="card-body">
                <h2 class="card-title">Assessment</h2>
            </div>
            <div class="flex flex-col">
                <p>{{ props.assessmentData }}</p>
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
            </div>
        </div>
        <div class="card w-96 bg-base-100  shadow-xl my-4">
            <div class="card-body">
                <h2 class="card-title justify-center">List of Question</h2>
            </div>
        </div>
        <div v-for="quesData in questionAnswerModal" class="card w-96 bg-base-100  shadow-xl my-4">
            <div class="card-body">
                <p>type : {{quesData.type}}</p>
                <h2 class="card-title">{{quesData.name}}</h2>
                <div v-for="ansData in quesData.answers">
                    {{ansData.name}}
                </div>
                <div class="card-actions">
                    <Link :href="route('question.edit',quesData.id)" class="btn btn-accent">Edit question</Link>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {onMounted, onUpdated, ref} from "vue";
import InputForm from "@/Components/inputForm.vue";
import AnswerForm from "@/Components/answerForm.vue";


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

</script>

<style>

</style>
