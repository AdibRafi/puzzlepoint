<template>
    <MainLayout>
        <QuestionForm store-method="question.store"/>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import QuestionForm from "@/Components/questionForm.vue";

const props = defineProps({
    question: Object,
})


</script>

<style scoped>

</style>
