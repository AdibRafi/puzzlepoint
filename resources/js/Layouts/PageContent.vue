<template>
    <div class="drawer-content flex flex-col">
        <Header :page-title="pageTitle"/>
        <main class="flex-1 overflow-y-auto pt-8 px-6 bg-base-200 min-h-screen">
            <ul v-if="!$page.props.auth.user.is_wizard_complete"
                class="steps w-full my-2">
                <li class="step step-primary">Add Classroom</li>
                <li :class="'step ' + (progressStep >= 2 ? 'step-primary' : '')">Add Student</li>
                <li :class="'step ' + (progressStep >= 3 ? 'step-primary' : '')">Add Topic</li>
                <li :class="'step ' + (progressStep >= 4 ? 'step-primary' : '')">Add Assessment</li>
                <li :class="'step ' + (progressStep >= 5 ? 'step-primary' : '')">Start Session</li>
                <li :class="'step ' + (progressStep >= 6 ? 'step-primary' : '')">End Assessment</li>
                <li :class="'step ' + (progressStep >= 7 ? 'step-primary' : '')">Archive</li>
            </ul>
            <div v-if="!$page.props.auth.user.is_wizard_complete" class="divider"/>
            <div v-if="$page.props.message.alertMessage"
                 class="alert alert-success w-full shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-check" bounce/>
                    <span class="mx-2">{{ $page.props.message.alertMessage }}</span>
                </div>
            </div>
            <div v-if="$page.props.message.warningMessage"
                 class="alert alert-warning w-full shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-exclamation"/>
                    <span class="mx-2">{{ $page.props.message.warningMessage }}</span>
                </div>
            </div>
            <slot/>
            <div class="mt-16"/>
        </main>
    </div>
</template>

<script setup>
import Header from "@/Layouts/Header.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

defineProps({
    pageTitle: String,
})

const progressStep = ref(0);
const wizardStatus = usePage().props.auth.user.wizard_status;

switch (wizardStatus) {
    case 'onCreateClassroom': {
        progressStep.value = 1;
        break;
    }
    case 'onAddStudent': {
        progressStep.value = 2;
        break;
    }
    case 'onCreateTopic': {
        progressStep.value = 3;
        break;
    }
    case 'onCreateAssessment': {
        progressStep.value = 4;
        break;
    }
    case 'onPublishAssessment': {
        progressStep.value = 4;
        break;
    }
    case 'onStartSession': {
        progressStep.value = 5;
        break;
    }
    case 'onEndAssessment' : {
        progressStep.value = 6;
        break
    }
    case 'onShowArchive': {
        progressStep.value = 7;
        break
    }
    default:
        break;
}

</script>
