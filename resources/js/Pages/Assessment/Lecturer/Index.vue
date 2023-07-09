<template xmlns="http://www.w3.org/1999/html">
    <Head title="Assessment"/>
    <Layout page-title="Assessment">
        <TitleCard :title="props.topicModal.name"
                   :top-right-button-label="($page.props.auth.user.is_wizard_complete && props.assessmentModal.is_publish === 1)
                   ? 'Edit Assessment' : '' "
                   @button-function="toEditAssessment">
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <Stat title="Total Question" :value="props.questionAnswerModal.length"
                      desc="Click Button to Add Question">
                    <div @click.prevent="toCreateQuestion"
                         :class="'stat-figure btn btn-circle ' +
                          (!isWizardComplete && wizardStatus === 'onEndAssessment' ? 'btn-disabled' : 'btn-primary')">
                        <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                    </div>
                </Stat>
                <Stat v-if="!props.assessmentModal.is_ready_publish"
                      title="Publish" desc="Click button to Publish">
                    <div @click.prevent="sendPublish"
                         :class="'stat-figure btn btn-circle ' +
                              ((wizardStatus === 'onPublishAssessment' || $page.props.auth.user.is_wizard_complete) ? 'btn-primary': 'btn-disabled')">
                        <font-awesome-icon icon="fa-solid fa-upload" size="xl"/>
                    </div>
                    <InputText input-type="number"
                               label-title="Duration of Assessment (in minutes)"
                               v-model="form.time"/>
                    <InputText label-title="Date to End Assessment"
                               input-type="datetime-local"
                               v-model="form.endPublishTime"
                               input-name="date"/>
                </Stat>
                <Stat v-else-if="props.assessmentModal.is_ready_publish"
                      title="End Assessment Session" :value="props.assessmentModal.time + ' Minutes'"
                      desc="Click Button to End">
                    <div @click.prevent="toEndAssessment"
                         class="stat-figure btn btn-circle btn-primary">
                        <font-awesome-icon icon="fa-solid fa-forward-step" size="xl"/>
                    </div>
                </Stat>
                <Stat title="End Date" :value="dateTime"/>
            </div>
            <div class="divider"/>
            <h2 class="card-title">List of Questions</h2>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="(questionData,index) in props.questionAnswerModal">
                    <Stat :title="'Question '+ (index + 1)"
                          desc="To edit, click the button">
                        <h2 class="card-title">{{ questionData.name }}</h2>
                        <div v-for="answerData in questionData.answers">
                            <p>{{ answerData.name }}</p>
                        </div>
                        <div @click.prevent="editQuestion(questionData.id)"
                             :class="'stat-figure btn btn-circle ' +
                          (!isWizardComplete && wizardStatus === 'onEndAssessment' ? 'btn-disabled' : 'btn-primary')">
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                        </div>
                    </Stat>
                </div>
            </div>
            <div v-if="wizardStatus === 'onCreateAssessment'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span class="ml-4">This will be Assessment Page. <br/> Click the Question Button.</span>
                </div>
            </div>
            <div v-else-if="wizardStatus === 'onPublishAssessment'"
                 class="alert alert-info shadow-lg my-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span class="ml-2">After you satisfied with the question, You can publish it.<br/> Specify the minutes and end date that you would like <br/> After that, click the publish button to continue</span>
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
                    To end early, click the end assessment session button. Click to Continue
                </div>
            </div>
            <button @click.prevent="router.get(route('topic.show',props.topicModal))"
                    class="btn btn-accent mt-10">Back to Topic
            </button>
        </TitleCard>
    </Layout>
</template>

<script setup>
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import InputText from "@/Components/InputText.vue";
import Stat from "@/Components/Stat.vue";


const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});

const form = useForm({
    assessment_id: props.assessmentModal.id,
    time: '',
    endPublishTime: '',
})

const isWizardComplete = usePage().props.auth.user.is_wizard_complete;
const wizardStatus = usePage().props.auth.user.wizard_status;

const today = new Date().toISOString().slice(0, 16);

const formatDate = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    const strTime = hours + ':' + minutes + ' ' + ampm;
    return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear() + "  " + strTime;
}

let dateTime;
if (props.assessmentModal.publish_end !== null) {
    dateTime = formatDate(new Date(props.assessmentModal.publish_end));
} else {
    dateTime = 'Not Publish'
}

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

const toEditAssessment = () => {
    router.get(route('assessment.edit', props.assessmentModal));
}

const editQuestion = (id) => {
    router.get(route('question.edit', id))
}



</script>
