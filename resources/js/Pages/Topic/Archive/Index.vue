<template>
    <Head title="Archive"/>
    <Layout>
        <TitleCard :title="props.classroomModal.name">
            <div v-if="wizardStatus === 'onShowArchive'"
                 class="alert alert-info shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span>Here will be the archive of the completed topics. <br/>
                    Click the completed topic to continue</span>
                </div>
            </div>
            <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                <div v-for="topicData in props.topicModuleModal" :key="topicData"
                     @click.prevent="showArchiveTopic(topicData.id)"
                     class="stats shadow bg-base-100 hover:bg-base-200 hover:cursor-pointer">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" size="xl"/>
                        </div>
                        <div class="stat-title text-lg">{{ topicData.name }}</div>
                    </div>
                </div>
            </div>
        </TitleCard>
        <div v-for="topicData in props.topicModal" :key="topicData">
            <Card :title="topicData.name">
                <p>Number of Modules = {{ topicData.no_of_modules }}</p>
                <template #actions>
                    <Link :href="route('topic.archive.show',topicData.id)"
                          class="btn btn-primary">
                        View
                    </Link>
                </template>
            </Card>
        </div>
    </Layout>
</template>


<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    classroomModal: Object,
    topicModuleModal: Object,
})

const wizardStatus = usePage().props.auth.user.wizard_status;

const showArchiveTopic = (id) => {
    router.get(route('topic.archive.show', id))
}
</script>
