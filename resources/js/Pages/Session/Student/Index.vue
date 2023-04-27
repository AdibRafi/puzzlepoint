<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p></p>
        </Card>
        <Card title="You have enter a session" class="my-4">
            Please wait your lecturer to start the topic session
        </Card>
    </MainLayout>
</template>

<script setup>
import {Head, Link} from "@inertiajs/vue3";
import {defineComponent} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";


const props = defineProps({
    topicModuleModal: Object,
})
</script>

<style scoped>

</style>
