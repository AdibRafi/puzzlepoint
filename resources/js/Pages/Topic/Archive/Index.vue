<template>
<MainLayout>
    <Card title="DEVELOPER">
        <p>{{ topicModal}}</p>
    </Card>
    <div v-for="topicData in props.topicModal" :key="topicData">
        <Card :title="topicData.name">
            <p>Number of Modules = {{topicData.no_of_modules}}</p>
            <template #actions>
                <Link :href="route('topic.archive.show',topicData.id)"
                      class="btn btn-primary">
                    View
                </Link>
            </template>
        </Card>
    </div>
</MainLayout>
</template>


<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {Link} from "@inertiajs/vue3";
const props = defineProps({
    topicModal: Object,
})
</script>
