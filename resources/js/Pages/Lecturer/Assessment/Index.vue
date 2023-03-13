<template>
    <Head title="Assessment"/>
    <MainLayout>
        <div class="card w-96 bg-base-100 pb-5 shadow-xl my-2">
            <div class="card-body">
                <h2 class="card-title">Assessment</h2>
            </div>
            <div class="flex flex-col">
                <p>{{props.assessment}}</p>
                <div class="divider">Question</div>
                <div class="card-actions justify-center">
                    <Link :href="route('question.create',{assessment_id:props.assessment})" class="btn btn-primary">Create</Link>
                    <button class="btn btn-primary">Edit/Delete</button>
                </div>
                <div class="divider">Assessment</div>
                <div class="card-actions justify-center">
                    <button class="btn btn-warning">Delete</button>
                </div>
            </div>
        </div>
        <div class="divider">List of Question</div>
        <div v-for="(data,index) in props.questionData" :key="data">
            <div class="card w-96 bg-base-100 shadow-xl my-2">
                <div class="card-body">
                    <h2 class="card-title">Question {{ index + 1 }}</h2>
                    <p>{{ data.name }}</p>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Create Assessment</h2>
                <input @keyup="setMin" min="1" v-model="questionNumber" type="number"
                       class="input input-bordered w-full max-w-xs"/>
                <p>{{ refTest }}</p>

                <!--                todo: kalau nk implement plus minus then silakan later-->
                <!--                <div class="w-auto py-2">-->
                <!--                    <div class="float-left inline">-->
                <!--                        <button class="btn btn-circle" @click="questionNumber&#45;&#45;">-->
                <!--                            <font-awesome-icon icon="fa-solid fa-minus"/>-->
                <!--                        </button>-->
                <!--                    </div>-->
                <!--                    <div class="float-left inline py-2 mx-4">-->
                <!--                        <p class="border text-xl">{{ questionNumber }}</p>-->
                <!--                    </div>-->
                <!--                    <div class="float-left inline">-->
                <!--                        <button class="btn btn-circle" @click="questionNumber++">-->
                <!--                            <font-awesome-icon icon="fa-solid fa-plus"/>-->
                <!--                        </button>-->
                <!--                    </div>-->
                <!--                </div>-->

            </div>
        </div>
        <!-- todo: do question one by one-->
        <form @submit.prevent="quesForm.post(route('assessment.store'))">
            <div v-for="(questionItems,qNum) in questionNumber" :key="questionItems" class="m-4">
                <div class="card w-96 bg-base-100 shadow-xl my-2">
                    <div class="card-body">
                        <div class="w-1/2">
                            <!--todo: (optional) do swap-->
                            <label class="label cursor-pointer">
                                <span class="label-text">One Answer</span>
                                <input type="radio" :name="'AnsType'+[questionItems]" id="radioAnsType"
                                       value="radio"
                                       class="radio checked:radio-secondary"
                                       v-model="quesForm.question_type[questionItems]"
                                       checked/>
                            </label>
                            <label class="label cursor-pointer">
                                <span class="label-text">Multiple Answer</span>
                                <input type="radio" :name="'AnsType'+[questionItems]" id="radioAnsType"
                                       value="check"
                                       class="radio checked:radio-secondary"
                                       v-model="quesForm.question_type[questionItems]"/>
                            </label>
                            <label class="label cursor-pointer">
                                <span class="label-text">Short answer</span>
                                <input type="radio" :name="'AnsType'+[questionItems]" id="radioAnsType"
                                       value="input"
                                       class="radio checked:radio-secondary"
                                       v-model="quesForm.question_type[questionItems]"/>
                            </label>
                            <label class="label cursor-pointer">
                                <span class="label-text">Long answer</span>
                                <input type="radio" :name="'AnsType'+[questionItems]" id="radioAnsType"
                                       value="textarea"
                                       class="radio checked:radio-secondary"
                                       v-model="quesForm.question_type[questionItems]"/>
                            </label>
                        </div>
                        <h2 class="card-title mt-2">Question {{ questionItems }} </h2>
                        <InputForm label-name="Question" input-placeholder="Type Question here"
                                   v-model="quesForm.question_name[qNum]"/>
                        <div class="card-actions flex flex-col">
                            <div class="form-control">
                                <div v-if="radioAnsType ==='radio'">
                                    <div v-for="(answerItems) in radioAnsNumber" :key="answerItems">
                                        <label class="label cursor-pointer">
                                            <input type="radio" :name="'radioAns'+[questionItems]" id="radioAns"
                                                   class="mr-2 radio"/>
                                            <input type="text" placeholder="Type answer here"
                                                   class="input input-bordered input-sm w-full max-w-xs"
                                                   v-model="quesForm.answer[answerItems]"/>
                                        </label>
                                    </div>
                                    <div class="my-2">
                                        <span @click="radioAnsNumber--"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-minus" class="scale-150 p-4"/>
                                        </span>
                                        <span @click="radioAnsNumber++"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-plus" class="scale-150 p-4"/>
                                        </span>
                                    </div>
                                </div>
                                <!--                                <AnswerForm radio-ans-type="radio" />-->
                                <div v-if="radioAnsType==='check'">
                                    <div v-for="ansData in checkAnsNumber">
                                        <label class="label cursor-pointer">
                                            <input type="checkbox" name="radio-10" class="mr-2 checkbox"/>
                                            <input type="text" placeholder="Type answer here"
                                                   class="input input-bordered input-sm w-full max-w-xs"
                                                   v-model="ansForm.answer_name[questionItems]"/>
                                        </label>
                                    </div>
                                    <div class="my-2">
                                        <span @click="checkAnsNumber--"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-minus" class="scale-150 p-4"/>
                                        </span>
                                        <span @click="checkAnsNumber++"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-plus" class="scale-150 p-4"/>
                                        </span>
                                    </div>
                                </div>
                                <!--                            <div v-else-if="radioAnsType==='input'">-->
                                <!--                                <InputForm label-name="Answer" input-placeholder="Type answer here"/>-->
                                <!--                            </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-96 bg-base-100 shadow-xl my-2 mb-4">
                <div class="card-body">
                    <div class="card-actions justify-center my-2">
                        <button type="submit" :disabled="quesForm.processing"
                                class="btn btn-primary">Create Assessment
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {onUpdated, ref} from "vue";
import InputForm from "@/Components/inputForm.vue";
import AnswerForm from "@/Components/answerForm.vue";

const questionNumber = ref(1);
const radioAnsType = ref('radio');
const radioAnsNumber = ref(1);
const test = ref(ref(1));
const refTest = ref([
    {
        idk: questionNumber
    },
    ref(2)
]);

const exampleData = {
    'topic_id': 1,
    'question_name': 'why does',
    'question_type': 'radio',
    'answer': {
        'name': {
            '1': 'test',
            '2': 'test',
            '3': 'test',
            '4': 'test',
        },
        'right_answer': '3'
    }
}

onUpdated(function () {
    console.log(test.value)
})

const checkAnsNumber = ref(1);

const props = defineProps({
    topicData: Object,
    questionData: Object,
    assessment: Object,

});

const quesForm = useForm({
    topic_id: props.topicData.topic_id,
    question_name: {},
    question_type: {},
    answer: {}
});

const testForm = useForm({
    topic_id: props.topicData.topic_id,
    question_name: {},
    question_type: {},

})

// todo: find a way to set radioAns n checkAns to never < 1
const setMin = () => {
    if (questionNumber.value < 1) {
        questionNumber.value = null;
    }
    if (radioAnsNumber.value < 1) {
        radioAnsNumber.value = null
    }
    if (checkAnsNumber.value < 1) {
        checkAnsNumber.value = null;
    }
}


</script>

<style>

</style>
