<template>
    <Head title="Archive"/>
    <Layout page-title="Archive">
        <Card title="DEVELOPER">

        </Card>
        <TitleCard :title="props.topic.name">
            <div v-if="wizardStatus === 'onShowArchive'"
                 class="alert alert-info shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Great! Now you can click the created topic</span>
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
                        <div class="stat-figure text-primary">
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
            <div class="divider my-10">Assessment</div>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div class="stats shadow bg-base-100 border-2">
                    <div class="stat">
                        <div class="stat-figure text-primary">
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
                    <input type="checkbox" id="modal" class="modal-toggle" />
                    <div class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Congratulations random Internet user!</h3>
                            <p class="py-4">You've been selected for a chance to get one year of subscription to use Wikipedia for free!</p>
                            <div class="modal-action">
                                <label for="modal" class="btn">Yay!</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider my-10">Question</div>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="questionData in props.questionAnswerModal" :key="questionData">
                    <div class="stats shadow bg-base-100 border-2 w-full">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <font-awesome-icon icon="fa-solid fa-clipboard" size="xl"/>
                            </div>
                            <div class="stat-title text-xl">{{questionData.name}}</div>
                            <div v-for="answerData in questionData.answers" :key="answerData"
                                class="stat-title">{{answerData.name}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </TitleCard>
        <!--        <Card :title="props.topic.name">-->
        <!--            <p>Number of Modules = {{ props.topic.no_of_modules }}</p>-->
        <!--        </Card>-->
        <!--        <div class="divider">Expert Group</div>-->
        <!--        <Card v-for="groupData in props.expertGroupModal" :key="groupData"-->
        <!--              :title="groupData.name">-->
        <!--            <p class="text-xl">{{ groupData.module.name }}</p>-->
        <!--            <p v-for="userData in groupData.users" :key="userData">-->
        <!--                {{ userData.name }}-->
        <!--            </p>-->
        <!--        </Card>-->
        <!--        <div class="divider">Jigsaw Group</div>-->
        <!--        <Card v-for="groupData in props.jigsawGroupModal" :key="groupData"-->
        <!--              :title="groupData.name">-->
        <!--            <p v-for="userData in groupData.users" :key="userData">-->
        <!--                {{ userData.name }}-->
        <!--            </p>-->
        <!--        </Card>-->
    </Layout>
</template>

<script setup>

import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {ref} from "vue";
import CardTable from "@/Components/CardTable.vue";
import {Head, usePage} from "@inertiajs/vue3";

const props = defineProps({
    topic: Object,
    moduleModal: Object,
    expertGroupModal: Object,
    jigsawGroupModal: Object,
    studentModal: Object,
    assessmentModal: Object,
    questionAnswerModal: Object,
    studentAssessmentModal: Object,
})

const numCompleteAssessmentStudent = ref(0);
const isModuleExpand = ref(false);
const wizardStatus = usePage().props.auth.user.wizard_status;
const setModuleExpand = () => {
    isModuleExpand.value = !isModuleExpand.value
    console.log(isModuleExpand.value)
}

console.log(props.studentAssessmentModal[0].pivot.marks)

for (let i = 0; i < props.studentAssessmentModal.length; i++) {
    if (props.studentAssessmentModal[i].pivot.marks !== null) {
        numCompleteAssessmentStudent.value++;
    }
}
</script>
