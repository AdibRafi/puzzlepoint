<template>
    <Head title="Archive"/>
    <Layout page-title="Archive">
        <TitleCard :title="props.topic.name"
                   :top-right-button-label="($page.props.auth.user.type === 'lecturer' && $page.props.auth.user.is_wizard_complete === 1) ?
                   'Duplicate Topic' : ''"
                   @button-function="duplicateTopic">
            <div v-if="wizardStatus === 'onShowArchive'"
                 class="alert alert-info shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Here this will be the information about the topics. <br/>
                        Scroll down to continue.
                    </span>
                </div>
            </div>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div class="stats shadow bg-base-100">
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
                <div class="stats shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
                        </div>
                        <div class="stat-title">Total Students</div>
                        <div class="stat-value">{{ props.studentModal.length }}</div>
                        <div class="stat-desc">.</div>
                    </div>
                </div>
                <div class="stats shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass-start" size="xl"/>
                        </div>
                        <div class="stat-title">Expert Time</div>
                        <div class="stat-value">{{ props.topic.max_time_expert }}</div>
                        <div class="stat-desc">For Expert Session</div>
                    </div>
                </div>
                <div class="stats shadow bg-base-100">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass-end" size="xl"/>
                        </div>
                        <div class="stat-title">Jigsaw Time</div>
                        <div class="stat-value">{{ props.topic.max_time_jigsaw }}</div>
                        <div class="stat-desc">For Jigsaw Session</div>
                    </div>
                </div>
            </div>
            <div class="divider mt-10">Expert Group</div>
            <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                <div v-for="groupData in props.expertGroupModal">
                    <CardTable :title="groupData.name"
                               card-style="">
                        <div class="overflow-x-auto">
                            <table class="table w-full table-compact">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="userData in groupData.users" :key="userData">
                                    <td>{{ userData.name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardTable>
                </div>
            </div>
            <div class="divider mt-10">Jigsaw Group</div>
            <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                <div v-for="groupData in props.jigsawGroupModal">
                    <CardTable :title="groupData.name"
                               card-style="">
                        <div class="overflow-x-auto">
                            <table class="table w-full table-compact">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Module</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="userData in groupData.users" :key="userData">
                                    <td>{{ userData.name }}</td>
                                    <td>{{ userData.pivot.module_name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardTable>
                </div>
            </div>
            <div class="divider my-10">Assessment</div>
            <div v-if="wizardStatus === 'onShowArchive'"
                 class="alert alert-info shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Here you can look the assessment <br/>
                    Click the user button to see in detail</span>
                </div>
            </div>
            <div v-if="$page.props.auth.user.type === 'lecturer'">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div class="stats shadow bg-base-100 border-2">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
                            </div>
                            <div class="stat-title">Total Question</div>
                            <div class="stat-value">{{ questionAnswerModal.length }}</div>
                        </div>
                    </div>
                    <div class="stats shadow bg-base-100 border-2">
                        <div class="stat">
                            <label for="modal" class="stat-figure btn btn-circle btn-primary">
                                <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
                            </label>
                            <div class="stat-title">Total Student Complete</div>
                            <div class="stat-value">{{ numCompleteAssessmentStudent }}</div>
                        </div>
                        <input type="checkbox" id="modal" class="modal-toggle"/>
                        <label for="modal" class="modal cursor-pointer">
                            <label class="modal-box relative" for="">
                                <div class="overflow-x-auto">
                                    <table class="table w-full">
                                        <thead>
                                        <tr>
                                            <th class="w-3/4">Name</th>
                                            <th>Marks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="studentData in studentsCompleteAssessment" :key="studentData">
                                            <td>{{ studentData.name }}</td>
                                            <td>
                                                <div class="badge badge-success">
                                                    {{ studentData.mark }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-for="studentData in studentsNotCompleteAssessment" :key="studentData">
                                            <td>{{ studentData.name }}</td>
                                            <td>
                                                <div class="badge badge-error">
                                                    {{ studentData.mark }}
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </label>
                        </label>
                    </div>
                </div>
            </div>
            <div v-else-if="$page.props.auth.user.type === 'student'">
                <GridLayout>
                    <Stat title="Assessment" :value="props.studentAssessmentModal.pivot.is_finish
                    ? (props.studentAssessmentModal.pivot.marks + ' Marks') : 'Not Complete'"/>
                </GridLayout>
            </div>
            <div class="divider my-10">Question</div>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="questionData in props.questionAnswerModal" :key="questionData">
                    <div class="stats shadow bg-base-100 border-2 w-full">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <font-awesome-icon icon="fa-solid fa-clipboard" size="xl"/>
                            </div>
                            <div class="stat-title text-xl">{{ questionData.name }}</div>
                            <div v-for="answerData in questionData.answers" :key="answerData"
                                 class="stat-title">{{ answerData.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider">Module Preview</div>
            <div v-for="moduleData in props.moduleModal" :key="moduleData">
                <Stat :title="moduleData.name" class="my-2">
                    <iframe v-if="moduleData.file_path"
                            class="mt-2"
                            :src="'../../../../../' + moduleData.file_path"
                            type="application/pdf"
                            width="100%" height="800"/>
                </Stat>

            </div>
            <div v-if="wizardStatus === 'onShowArchive'"
                 class="alert alert-info shadow-lg mt-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>You have complete the tutorial! <br/>
                    Press the button to return to main menu</span>
                </div>
                <div class="flex-none">
                    <button @click.prevent="router.get(route('end.wizard'))"
                            class="btn btn-ghost">Finish Tutorial
                    </button>
                </div>
            </div>
            <button @click="back"
                    class="btn btn-accent">Back
            </button>
        </TitleCard>
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {reactive, ref} from "vue";
import CardTable from "@/Components/CardTable.vue";
import {Head, router, usePage} from "@inertiajs/vue3";
import Stat from "@/Components/Stat.vue";
import GridLayout from "@/Components/GridLayout.vue";

const props = defineProps({
    topic: Object,
    moduleModal: Object,
    classroomModal: Object,
    expertGroupModal: Object,
    jigsawGroupModal: Object,
    studentModal: Object,
    assessmentModal: Object,
    questionAnswerModal: Object,
    studentAssessmentModal: Object,
})

const studentsCompleteAssessment = reactive([]);
const studentsNotCompleteAssessment = reactive([]);

const numCompleteAssessmentStudent = ref(0);
const isModuleExpand = ref(false);
const wizardStatus = usePage().props.auth.user.wizard_status;
const setModuleExpand = () => {
    isModuleExpand.value = !isModuleExpand.value
}


for (let i = 0; i < props.studentAssessmentModal.length; i++) {
    if (props.studentAssessmentModal[i].pivot.marks !== null) {
        numCompleteAssessmentStudent.value++;
        studentsCompleteAssessment.push({
            name: props.studentAssessmentModal[i].name,
            mark: props.studentAssessmentModal[i].pivot.marks,
        })
    } else {
        studentsNotCompleteAssessment.push({
            name: props.studentAssessmentModal[i].name,
            mark: 'NotComplete',
        });
    }
}

const back = () => {
    router.get(route('topic.archive.index'), {
        classroom_id: props.classroomModal.id
    })
}

const duplicateTopic = () => {
    if (confirm('Are you sure to Duplicate Topic?')) {
        router.post(route('topic.duplicate',
            {
                topic_id: props.topic.id
            }
        ))
    } else {
        router.reload();
    }
}
</script>
