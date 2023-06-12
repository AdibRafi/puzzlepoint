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
                             :class="'stat-figure btn btn-circle btn-primary ' +
                              (wizardStatus === 'onCreateAssessment'? 'btn-disabled': '')">
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
                        <div @click.prevent="toEndAssessment"
                             class="stat-figure btn btn-circle btn-primary">
                            <font-awesome-icon icon="fa-solid fa-forward-step" size="xl"/>
                        </div>
                        <div class="stat-title">End Assessment Session</div>
                        <div class="stat-value">
                            {{ props.assessmentModal.time }} Minutes
                        </div>
                        <div class="stat-desc">Click Button to End</div>
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
            <div v-else-if="wizardStatus === 'onPublishAssessment'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Since you already create a question, You can publish it.<br/> Specify the minutes that you would like students to answer the assessment. <br/> After that, click the publish button to continue</span>
                </div>
            </div>
            <div v-else-if="wizardStatus === 'onStartSession'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Great! Now go back to topic menu to start the session</span>
                </div>
            </div>
            <div v-else-if="wizardStatus === 'onEndAssessment'"
                 class="alert alert-info shadow-lg mt-10">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>
                    Since the Assessment is not time specified event. <br/>
                        You can end it by clicking the end button.
               </span>
                </div>
            </div>
            <button @click.prevent="router.get(route('topic.show',props.topicModal))"
                    class="btn btn-accent mt-10">Back to Topic
            </button>
        </TitleCard>
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

const toEndAssessment = () => {
    router.get(route('end.assessment.session'), {
        assessment_id: props.assessmentModal.id
    });
}

onMounted(() => {
    router.reload();
})
</script>
