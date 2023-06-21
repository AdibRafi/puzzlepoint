<template>
    <Head :title="props.classroom.name"/>
    <Layout :page-title="props.classroom.name">
        <div v-if="$page.props.auth.user.type === 'lecturer'"
             class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
            <div class="state shadow bg-base-100">
                <div class="stat">
                    <Link :href="route('classroom.add-student.create',{
                        classroom_id: props.classroom.id
                    })"
                          :class="'stat-figure btn btn-circle ' +
                          (wizardStatus === 'onAddStudent' || isWizardComplete ? 'btn-primary':'btn-disabled')">
                        <font-awesome-icon icon="fa-solid fa-plus" size="xl"/>
                    </Link>
                    <div class="stat-title">Total Student</div>
                    <div class="stat-value">{{ props.studentModal.length }}</div>
                    <div class="stat-desc">Click the button to add students</div>
                </div>
                <div v-if="wizardStatus === 'onAddStudent'"
                     class="alert alert-info shadow-lg">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Now Click the + Button to add Student</span>
                    </div>
                </div>
            </div>
            <div class="shadow bg-base-100">
                <div class="stat">
                    <Link :href="route('topic.create',{classroom_id : props.classroom.id})"
                          :class="'stat-figure btn btn-circle ' +
                              (wizardStatus === 'onCreateTopic' || isWizardComplete ? 'btn-primary':'btn-disabled')">
                        <font-awesome-icon icon="fa-solid fa-plus" size="xl"/>
                    </Link>
                    <div class="stat-title">Total Topic</div>
                    <div class="stat-value">{{ props.topicModal.length }}</div>
                    <div class="stat-desc">
                        To add more, Click the + Button
                    </div>
                </div>
            </div>
            <div v-if="wizardStatus === 'onCreateTopic'"
                 class="alert alert-info shadow-lg">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span class="ml-4">Now Click the + Button to add Topic</span>
                </div>
            </div>
            <div class="state shadow bg-base-100">
                <div class="stat">
                    <Link :href="route('topic.archive.index',{classroom_id: props.classroom.id})"
                          :class="'stat-figure btn btn-circle ' +
                          (wizardStatus === 'onShowArchive' || isWizardComplete ? 'btn-primary':'btn-disabled')">
                        <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" size="xl"/>
                    </Link>
                    <div class="stat-title">Total Archive</div>
                    <div class="stat-value">{{ props.topicArchiveModal.length }}</div>
                    <div class="stat-desc">Click the button to see Archive</div>
                </div>
            </div>
        </div>
        <div v-if="wizardStatus === 'onShowArchive'"
             class="alert alert-info shadow-lg my-4">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>If you have completed both jigsaw learning session and assessment, <br/>
                That topic will be moved to archive. <br/>
                    Click the archive button to continue
                </span>
            </div>
        </div>
        <div v-else-if="wizardStatus === 'onPublishAssessment'"
             class="alert alert-info shadow-lg my-4">
            <div>
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>Great! You have completed the jigsaw learning session! <br/>
                    Click the topic to continue.
                </span>
            </div>
        </div>
        <TitleCard :title="props.classroom.name"
                   :desc-title="$page.props.auth.user.type === 'lecturer' ? props.classroom.join_code : ''"
                   :tooltip-desc-text="wizardStatus === 'onAddStudent' ? 'Share this code to student to join' : ''">
            <!--:tooltip-btn-text="wizardStatus === 'onCreateTopic' ? 'This will be proceed to edit classroom' : ''"-->

            <div v-if="wizardStatus === 'onStartSession' || wizardStatus === 'onCreateAssessment'"
                 class="alert alert-info shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Great! Now you can click the created topic</span>
                </div>
            </div>
            <h2 class="card-title">List of Created Topics</h2>
            <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                <div v-for="data in topicModal" :key="data">
                    <CardClick :title="data.name"
                               :is_new="data.is_new"
                               @click="goTopic(data.id)">
                        <p>Modules = {{ data.no_of_modules }}</p>
                    </CardClick>
                </div>
            </div>
        </TitleCard>
    </Layout>
</template>

<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import CardClick from "@/Components/CardClick.vue";

const props = defineProps({
    classroom: Object,
    topicModal: Object,
    topicArchiveModal: Object,
    studentModal: Object,

})

const wizardStatus = usePage().props.auth.user.wizard_status;
const isWizardComplete = usePage().props.auth.user.is_wizard_complete;

const goTopic = (id) => {
    router.get(route('topic.show', id))
}

// console.log(props.topicModal.length);
const destroyClassroom = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('classroom.destroy', id))
    }
}

const destroyTopic = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('topic.destroy', id));
    }
}

</script>

