<template>
    <Head title="Assessment"/>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>assessment {{ props.assessmentModal }}</p>
        </Card>
        <Card title="Assessment">
            <p>For {{ props.topicModal.name }}</p>
            <p>You have {{ props.assessmentModal.time }} minutes to answer</p>
            <p>Good Luck!</p>
            <template #actions>
                <Link :href="route('student.assessment.session',{topic_id:props.topicModal.id})"
                class=" btn btn-primary">
                    Start
                </Link>
            </template>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {Head, Link} from "@inertiajs/vue3";

const props = defineProps({
    topicModal: Object,
    assessmentModal: Object,
})

</script>
