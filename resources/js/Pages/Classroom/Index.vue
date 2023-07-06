<template>
    <Head title="Dashboard"/>
    <Layout page-title="Classroom">
        <div v-if="!$page.props.auth.user.is_wizard_complete">
            <TitleCard title="Classroom List"
                       :top-right-button-label="wizardStatus === 'onCreateClassroom' ? 'Add Class' : ''"
                       @button-function="router.get(route('classroom.create'))"
                       :tooltip-btn-text="wizardStatus === 'onCreateClassroom' ? 'Lets Start by adding a class' : ''">
                <div v-if="wizardStatus === 'onAddStudent'"
                     class="alert alert-info shadow-lg mb-10">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-2">Great! Click the Classroom that you created</span>
                    </div>
                </div>
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div v-for="data in classroomData" :key="data">
                        <CardClick :title="data.name"
                                   @click="goClassroom(data.id)"
                                   :is_new="data.is_new">
                            <p>{{ data.subject_code }}</p>
                        </CardClick>
                    </div>
                </div>
            </TitleCard>

            <input type="checkbox" id="my-modal" class="modal-toggle"
                   v-model="openModal"/>
            <div class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Welcome to PuzzlePoint
                        <span class="mx-4">
                        <font-awesome-icon icon="fa-solid fa-hand" shake size="xl"/>
                     </span>
                    </h3>
                    <p class="py-4">Let's get started! Are you up for a tutorial?</p>
                    <div class="modal-action">
                        <button @click.prevent="updateWizard"
                                class="btn btn-warning">No
                        </button>
                        <button @click.prevent="openModalFunction"
                                class="btn btn-primary">Yes!
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <TitleCard title="Classroom List" top-right-button-label="Add Class"
                       @button-function="router.get(route('classroom.create'))">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div v-for="data in classroomData" :key="data">
                        <CardClick :title="data.name"
                                   @click="goClassroom(data.id)"
                                   :is_new="data.is_new">
                            <p>{{ data.subject_code }}</p>
                        </CardClick>
                    </div>
                </div>
            </TitleCard>
        </div>
    </Layout>

</template>


<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {onMounted, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import CardClick from "@/Components/CardClick.vue";

const props = defineProps({
    classroomData: Object,
})

const openModal = ref(false);
const wizardStatus = usePage().props.auth.user.wizard_status

const goClassroom = (id) => {
    router.get(route('classroom.show', id))
}

onMounted(() => {
    if (usePage().props.auth.user.type === 'lecturer' &&
        wizardStatus === 'onCreateClassroom') {
        openModal.value = true;
    }
})
const openModalFunction = () => {
    openModal.value = !openModal.value;
}

const updateWizard = () => {
    router.get(route('user.update.wizard'))
}
const destroy = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('classroom.destroy', id))
    }
}

</script>
