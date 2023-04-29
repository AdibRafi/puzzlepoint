<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <p>{{props.questionAnswerModal[0].answers}}</p>
        </Card>
        <form @submit.prevent="form.post(route('student.assessment.check.answer'))">
            <Card :title="question.title"
                v-for="(question, index) in questions" :key="index">
                <div v-if="question.type === 'radio'">
                    <div v-for="(option, optionIndex) in question.options" :key="optionIndex">
                        <input type="radio" :id="`option_${optionIndex}`" :value="option" v-model="question.answer">
                        <label :for="`option_${optionIndex}`">{{ option }}</label>
                    </div>
                </div>
                <div v-else-if="question.type === 'check'">
                    <div v-for="(option, optionIndex) in question.options" :key="optionIndex">
                        <input type="checkbox" :id="`option_${optionIndex}`" :value="option" v-model="question.answers">
                        <label :for="`option_${optionIndex}`">{{ option }}</label>
                    </div>
                </div>
            </Card>
            <button type="submit" class="btn" :disabled="form.processing">Submit</button>
        </form>
    </MainLayout>
</template>

<script setup>
import {reactive, ref} from 'vue';
import {useForm} from '@inertiajs/inertia-vue3';
import Card from "@/Components/Card.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const quizTitle = 'My Awesome Quiz';

const props = defineProps({
    topicModal: Object,
    assessmentModal: Object,
    questionAnswerModal: Object,
})

const questions = reactive([]);

for (let i = 0; i < props.questionAnswerModal.length; i++) {
    const ans = ref([]);
    const rightAns = ref([]);
    for (let j = 0; j < props.questionAnswerModal[i].answers.length; j++) {
        ans.value.push(props.questionAnswerModal[i].answers[j].name)
        console.log(props.questionAnswerModal[i].answers[j].name)
    }
    if (props.questionAnswerModal[i].type === 'radio') {
        questions.push({
            id: props.questionAnswerModal[i].id,
            title: props.questionAnswerModal[i].name,
            type: props.questionAnswerModal[i].type,
            options: ans,
            answer: ''
        });
    } else {
        questions.push({
            id: props.questionAnswerModal[i].id,
            title: props.questionAnswerModal[i].name,
            type: props.questionAnswerModal[i].type,
            options: ans,
            answers: []
        });
    }
}

const form = useForm({
    answers: questions,
});

</script>
