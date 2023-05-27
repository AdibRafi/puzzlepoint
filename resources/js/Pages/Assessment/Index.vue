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
                <div v-if="!props.assessmentModal.is_publish"
                     class="stats shadow bg-base-100 border-2">
                    <div class="stat">
                        <div @click.prevent="sendPublish"
                             class="stat-figure btn btn-circle btn-primary">
                            <font-awesome-icon icon="fa-solid fa-upload" size="xl"/>
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
                <div v-else
                     class="stats shadow bg-base-100 border-2">
                    <div class="stat">
                        <div @click.prevent="toStartAssessment"
                             class="stat-figure btn btn-circle btn-primary">
                            <font-awesome-icon icon="fa-solid fa-play" size="xl"/>
                        </div>
                        <div class="stat-title">Start</div>
                        <div class="stat-value">
                            {{ props.assessmentModal.time }} Minutes
                        </div>
                        <div class="stat-desc">Click Button to Start</div>
                    </div>
                </div>
            </div>
            <div v-if="wizardStatus === 'onCreateAssessment'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>This will be Assessment Page. <br/> Click the Question Button.</span>
                </div>
            </div>
            <div v-else-if="wizardStatus === 'onStartSession'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>After you're done, you can publish it.<br/> Specify the minutes that you would like students to answer the assessment. <br/> After that, click the publish button to continue</span>
                </div>
            </div>
            <div v-if="wizardStatus === 'onStartAssessment'"
                 class="alert alert-info shadow-lg mt-10">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>
                    Click the Start button to Start!
               </span>
                </div>
            </div>
        </TitleCard>
        <!--        <Card title="Assessment">-->
        <!--            <div class="card-actions justify-center my-2">-->
        <!--                <Link :href="route('question.create',{assessment_id:props.assessmentModal.id})"-->
        <!--                      class="btn btn-primary">Create Question-->
        <!--                </Link>-->
        <!--            </div>-->
        <!--            <div class="card-actions justify-center">-->
        <!--                <button class="btn btn-warning"-->
        <!--                        @click="destroyAssessment(props.assessmentModal.id)">-->
        <!--                    Delete Assessment-->
        <!--                </button>-->
        <!--            </div>-->
        <!--            <div class="divider"/>-->
        <!--            <form @submit.prevent="form.post(route('assessment.publish'))">-->
        <!--                <InputForm label-name="Time"-->
        <!--                           input-type="number"-->
        <!--                           input-placeholder="in minutes" class="mb-4"-->
        <!--                           v-model="form.time"/>-->
        <!--                <button type="submit" :disabled="form.processing" class="btn btn-accent">-->
        <!--                    Publish-->
        <!--                </button>-->
        <!--            </form>-->
        <!--        </Card>-->
        <!--        <Card class="my-4">-->
        <!--            <h2 class="card-title">List of Question</h2>-->
        <!--        </Card>-->
        <!--        <Card :title="quesData.name" v-for="quesData in questionAnswerModal" class="my-4">-->
        <!--            <p>type: {{ quesData.type }}</p>-->
        <!--            <div v-for="ansData in quesData.answers">-->
        <!--                <p>{{ ansData.name }}</p>-->
        <!--            </div>-->
        <!--            <template #actions>-->
        <!--                <Link :href="route('question.edit',quesData)" class="btn btn-accent">Edit question</Link>-->
        <!--            </template>-->
        <!--        </Card>-->
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import InputForm from "@/Components/InputForm.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import InputText from "@/Components/InputText.vue";
import {onMounted} from "vue";


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

const form = useForm({
    assessment_id: props.assessmentModal.id,
    time: '',
})

const wizardStatus = usePage().props.auth.user.wizard_status;

const toCreateQuestion = () => {
    router.get(route('question.create'), {
        assessment_id: props.assessmentModal.id
    })
}

const sendPublish = () => {
    form.post(route('assessment.publish'))
}

const toStartAssessment = () => {
    router.get();
}

onMounted(() => {
    router.reload();
})
</script>
