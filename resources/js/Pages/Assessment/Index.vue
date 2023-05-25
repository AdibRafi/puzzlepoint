<template xmlns="http://www.w3.org/1999/html">
    <Head title="Assessment"/>
    <Layout page-title="Assessment">
        <Card title="DEVELOPER" class="mb-4">
            <p>Assessment Object</p>
            <p>{{ props.assessmentModal }}</p>
        </Card>
        <TitleCard :title="props.topicModal.name" top-right-button-label="Edit Assessment"
                   @button-function="">
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div class="stats shadow bg-base-100 border-2">
                    <div class="stat">
                        <div @click.prevent="toCreateQuestion"
                             class="stat-figure btn btn-circle btn-primary">
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                        </div>
                        <div class="stat-title">Total Question</div>
                        <div class="stat-value">{{ questionAnswerModal.length }}</div>
                        <div class="stat-desc">Click Button to Add Question</div>
                    </div>
                </div>
                <div class="stats shadow bg-base-100 border-2">
                    <div class="stat">
                        <div @click.prevent="sendPublish"
                             class="stat-figure btn btn-circle btn-primary">
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                        </div>
                        <div class="stat-title">Publish</div>
                        <div class="stat-value">
                            <InputText input-type="number"
                                       label-title="How Many Minutes?"
                                v-model="form.time"/>
                        </div>
                        <div class="stat-desc">Click Button to Publish</div>
                    </div>
                </div>
            </div>
        </TitleCard>
        <Card title="Assessment">
            <div class="card-actions justify-center my-2">
                <Link :href="route('question.create',{assessment_id:props.assessmentModal.id})"
                      class="btn btn-primary">Create Question
                </Link>
            </div>
            <div class="card-actions justify-center">
                <button class="btn btn-warning"
                        @click="destroyAssessment(props.assessmentModal.id)">
                    Delete Assessment
                </button>
            </div>
            <div class="divider"/>
            <form @submit.prevent="form.post(route('assessment.publish'))">
                <InputForm label-name="Time"
                           input-type="number"
                           input-placeholder="in minutes" class="mb-4"
                           v-model="form.time"/>
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
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import InputForm from "@/Components/InputForm.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import InputText from "@/Components/InputText.vue";


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

const form = useForm({
    topic_id: props.topicModal.id,
    time: '',
})

const toCreateQuestion = () => {
    router.get(route('question.create'), {
        assessment_id: props.assessmentModal.id
    })
}

const sendPublish = () => {
    form.post(route('assessment.publish'))
}

const destroyAssessment = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('assessment.destroy', id))
    }
}
</script>
