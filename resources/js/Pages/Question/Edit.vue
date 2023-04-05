<template>
    <MainLayout>
        <form>
            <p>{{props.questionAnswerModal.answers}}</p>
            <QuestionForm :assessment-id="props.assessment_id" :question-name="props.questionAnswerModal.name"
                          :question-type="props.questionAnswerModal.type" :answer-name="props.questionAnswerModal.answers"
                          :right-answer="props.questionAnswerModal.answers"
            :check-ans-number="props.questionAnswerModal.answers.length"
            :radio-ans-number="props.questionAnswerModal.answers.length"/>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import QuestionForm from "@/Components/questionForm.vue";

const props = defineProps({
    questionAnswerModal: Object,
    assessment_id: Object
})


</script>

<style scoped>

</style>
