<template>
    <Head title="Assessment"/>
    <Layout>
        <!--        <Card title="DEVELOPER">-->
        <!--            <p>assessment {{ props.assessmentModal }}</p>-->
        <!--        </Card>-->
        <TitleCard title="Assessment">
            <GridLayout>
                <Stat title="Topic Name" :value="props.topicModal.name"/>
                <Stat title="Duration of Assessment"
                      :value="props.assessmentModal.time + ' Minutes'"
                      figure="fa-solid fa-hourglass"/>
                <Stat value="Good Luck!"/>
                <Stat>
                    <button class="btn btn-primary"
                            @click.prevent="toStartAssessment">Start Assessment
                    </button>
                </Stat>
            </GridLayout>
        </TitleCard>
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
    </Layout>
</template>

<script setup>
import Card from "@/Components/Card.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import Stat from "@/Components/Stat.vue";
import GridLayout from "@/Components/GridLayout.vue";

const props = defineProps({
    topicModal: Object,
    assessmentModal: Object,
})

const toStartAssessment = () => {
    router.get(route('student.assessment.session', {
        topic_id: props.topicModal.id
    }));
}


</script>
