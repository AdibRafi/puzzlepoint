<template>
    <SessionLayout :page-title="'Assessment: ' + props.topicModal.name">
        <form @submit.prevent="form.post(route('student.assessment.check.answer'))">
            <div class="flex flex-col items-center">
                <div class="shadow bg-base-100 w-96 border-2 border-secondary">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            <font-awesome-icon icon="fa-solid fa-hourglass-half" size="xl" spin/>
                        </div>
                        <div class="stat-title">Time</div>
                        <div class="stat-value">{{ minuteCounter }}:{{ secondCounter }}</div>
                    </div>
                </div>
                <Card :title="question.name"
                      v-for="(question, index) in questions" :key="index">
                    <div v-if="question.type === 'radio'">
                        <div v-for="(option, optionIndex) in question.options" :key="optionIndex">
                            <label class="cursor-pointer">
                                <input type="radio" :id="`option_${optionIndex}`"
                                       class="mr-2 radio" :value="option"
                                       v-model="question.answer">
                                <label :for="`option_${optionIndex}`">{{ option }}</label>
                            </label>
                        </div>
                    </div>
                    <div v-else-if="question.type === 'check'">
                        <div v-for="(option, optionIndex) in question.options" :key="optionIndex">
                            <label class="cursor-pointer">
                                <input type="checkbox" :id="`option_${optionIndex}`"
                                       class="mr-2 checkbox" :value="option"
                                       v-model="question.answers">
                                <label :for="`option_${optionIndex}`">{{ option }}</label>
                            </label>
                        </div>
                    </div>
                </Card>
                <Card>
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Submit</button>
                </Card>
            </div>
        </form>
    </SessionLayout>
</template>

<script setup>
import {onUnmounted, reactive, ref} from 'vue';
import {useForm} from '@inertiajs/inertia-vue3';
import Card from "@/Components/Card.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import SessionLayout from "@/Layouts/SessionLayout.vue";
import Stat from "@/Components/Stat.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {router} from "@inertiajs/vue3";


const props = defineProps({
    topicModal: Object,
    assessmentModal: Object,
    questionAnswerModal: Object,
})

const questions = reactive([]);
const minuteCounter = ref(props.assessmentModal.time);
const secondCounter = ref(0);

for (let i = 0; i < props.questionAnswerModal.length; i++) {
    const ans = ref([]);
    for (let j = 0; j < props.questionAnswerModal[i].answers.length; j++) {
        ans.value.push(props.questionAnswerModal[i].answers[j].name)
    }
    if (props.questionAnswerModal[i].type === 'radio') {
        questions.push({
            id: props.questionAnswerModal[i].id,
            name: props.questionAnswerModal[i].name,
            type: props.questionAnswerModal[i].type,
            options: ans,
            answer: ''
        });
    } else {
        questions.push({
            id: props.questionAnswerModal[i].id,
            name: props.questionAnswerModal[i].name,
            type: props.questionAnswerModal[i].type,
            options: ans,
            answers: []
        });
    }
}

const form = useForm(questions);

const interval = setInterval(() => {
    if (secondCounter.value > 0) {
        secondCounter.value--;
    } else {
        secondCounter.value = 59;
        minuteCounter.value--;
    }
}, 1000)

onUnmounted(() => {
    clearInterval(interval);
})
</script>
