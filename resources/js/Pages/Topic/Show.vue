<template>
    <Head title="Topic"/>
    <Layout page-title="Topic Menu">
        <TitleCard :title="props.topic.name"
                   :top-right-button-label="(isWizardComplete === 1 && $page.props.auth.user.type === 'lecturer') ? 'Edit Topic': ''"
                   @button-function="editTopic">
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div class="shadow bg-base-100 border-2">
                    <div class="stat">
                        <div class="stat-figure btn btn-circle btn-primary" @click="setModuleExpand">
                            <font-awesome-icon icon="fa-solid fa-angle-down"
                                               size="xl"
                                               :class="'delay-400 duration-500 transition-all ' + (isModuleExpand ? 'rotate-180' : '')"/>
                        </div>
                        <div class="stat-title">No of Modules</div>
                        <div class="stat-value">{{ props.topic.no_of_modules }}</div>
                        <div class="stat-desc">To see the list, Click Button</div>
                        <p v-if="isModuleExpand"
                           v-for="moduleData in props.moduleModal" :key="moduleData"
                           class="stat-desc">{{ moduleData.name }}</p>
                    </div>
                </div>
                <div class="border-2 shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
                        </div>
                        <div class="stat-title">Total Students</div>
                        <div class="stat-value">{{ props.studentModal.length }}</div>
                        <div class="stat-desc">In this Classroom</div>
                    </div>
                </div>
                <div class="border-2 shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass-start" size="xl"/>
                        </div>
                        <div class="stat-title">Expert Time</div>
                        <div class="stat-value">{{ props.topic.max_time_expert }}</div>
                        <div class="stat-desc">For Expert Session</div>
                    </div>
                </div>
                <div class="border-2 shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass-end" size="xl"/>
                        </div>
                        <div class="stat-title">Jigsaw Time</div>
                        <div class="stat-value">{{ props.topic.max_time_jigsaw }}</div>
                        <div class="stat-desc">For Jigsaw Session</div>
                    </div>
                </div>
                <div class="border-2 shadow bg-base-100">
                    <div class="stat">
                        <div
                            :class="'stat-figure btn btn-circle ' +
                        ((wizardStatus === 'onStartSession' || isAssessmentComplete)
                         || ($page.props.auth.user.type === 'student' && props.assessmentModal.pivot) || ($page.props.auth.user.type === 'student' && props.assessmentModal.pivot === null)? 'btn-disabled':'btn-primary')"
                            @click.prevent="toAssessment(props.topic.id)">
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                        </div>
                        <div class="stat-title">Assessment</div>
                        <div class="stat-value">{{
                                props.assessmentModal.time !== null
                                    ? props.assessmentModal.time + ' Minutes' : 'Not Started'
                            }}
                        </div>
                        <div class="stat-desc">To go to assessment page, click button</div>
                    </div>
                </div>
                <div class="border-2 shadow bg-base-100">
                    <div class="stat">
                        <div
                            :class="'stat-figure btn btn-circle ' +
                        ((wizardStatus !== 'onStartSession' && !isWizardComplete) || isSessionComplete ? 'btn-disabled':'btn-primary')"
                            @click.prevent="openSession">
                            <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" size="xl"/>
                        </div>
                        <div class="stat-title">Start Session</div>
                        <div class="stat-value text-2xl">{{ dateTime }}</div>
                        <div class="stat-desc">Click button and it will bring you
                            <br/>to the session page
                        </div>
                    </div>
                </div>
            </div>
        </TitleCard>
        <div v-if="wizardStatus === 'onStartSession' || wizardStatus === 'onCreateAssessment'"
             class="alert alert-info shadow-lg mt-10">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span class="whitespace-pre-line ml-4">
                  {{
                        wizardStatus === 'onStartSession' ?
                            'Click the Start Session Button to Continue'
                            :
                            'This will be the topic info. \n You will need to create an assessment first before starting the session \n Click the assessment button to continue'
                    }}
               </span>
            </div>
        </div>
        <div v-else-if="wizardStatus === 'onPublishAssessment'"
             class="alert alert-info shadow-lg mt-10">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>
                    Click back to the Assessment Page
               </span>
            </div>
        </div>
        <div v-else-if="wizardStatus === 'onEndAssessment'"
             class="alert alert-info shadow-lg mt-10">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>
                    You finished the session! <br/>
                    When you finish your session, the assessment will automatically publish to students. <br/>
                    The assessment will automatically end it with the given end date of the assessment. <br/> <br/>
                    Or you can end it early by go to assessment page.
               </span>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import TitleCard from "@/Components/TitleCard.vue";
import Card from "@/Components/Card.vue";
import {isHTMLTag} from "@vue/shared";
import {toHtml} from "@fortawesome/fontawesome-svg-core";

const props = defineProps({
    topic: Object,
    moduleModal: Object,
    studentModal: Object,
    assessmentModal: Object,
})

const formatDate = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    const strTime = hours + ':' + minutes + ' ' + ampm;
    return (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
}

const dateTime = formatDate(new Date(props.topic.date_time))

const isModuleExpand = ref(false);
const wizardStatus = usePage().props.auth.user.wizard_status
const isSessionComplete = props.topic.is_complete;
const isAssessmentComplete = props.assessmentModal.is_complete;
const isWizardComplete = usePage().props.auth.user.is_wizard_complete;
// const isStudentFinishAssessment = (props.assessmentModal.pivot) === null ? false : props.assessmentModal.pivot.is_finish;
// console.log(isStudentFinishAssessment);

const setModuleExpand = () => {
    isModuleExpand.value = !isModuleExpand.value
    console.log(isModuleExpand.value)
}

const openSession = () => {
    window.open(route('lecturer.session.index', {topic_id: props.topic.id}), '_blank')
}

const toAssessment = (id) => {
    router.get(route('assessment.index'), {
        topic_id: id
    })
}

const editTopic = () => {
    router.get(route('topic.edit', props.topic));
}
</script>
