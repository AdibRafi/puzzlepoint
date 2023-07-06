<template>
    <Head title="Question"/>
    <Layout>
        <form @submit.prevent="form.put(route('question.update',props.questionAnswerModal))">
            <TitleCard title="Edit Question" top-right-button-label="Destroy"
                       @button-function="destroyQuestion">
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="radio" name="questionType" value="radio"
                               v-model="form.type" class="hidden peer"
                               @click="resetAns">
                        <label for="radio"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Single</p>
                     <p class="w-full">There will be only one answer in a question</p>
                  </span>
                            <font-awesome-icon icon="fa-regular fa-square" size="xl"/>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="check" name="questionType" value="check"
                               v-model="form.type" class="hidden peer"
                               @click="resetAns">
                        <label for="check"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Multiple</div>
                                <div class="w-full">There will be multiple answer in a question</div>
                            </div>
                            <font-awesome-icon icon="fa-regular fa-clone" size="xl"/>
                        </label>
                    </li>
                </ul>
                <div class="divider"/>
                <InputText label-title="Question" v-model="form.name"/>
                <div v-if="form.type === 'radio'">
                    <div v-for="(answerData,index) in form.answer">
                        <div class="inline-block mr-4 mt-12">
                            <input type="radio" name="radioAns" id="radioAns" class="radio absolute mt-5"
                                   :value="index"
                                   v-model="radioRightAnswer">
                        </div>
                        <div class="inline-block w-3/4">
                            <InputText container-style="input-md"
                                       :label-title="'Answer '"
                                       v-model="answerData.name"/>
                        </div>
                    </div>
                    <div class="mt-16"/>
                    <!--                    <div class="mt-16">-->
                    <!--                        <span @click="radioAnsNumber&#45;&#45;"-->
                    <!--                              class="btn btn-circle float-left inline scale-90">-->
                    <!--                            <font-awesome-icon icon="fa-solid fa-minus"-->
                    <!--                                               class="scale-150 p-4"/>-->
                    <!--                        </span>-->
                    <!--                        <span @click="radioAnsNumber++"-->
                    <!--                              class="btn btn-circle float-left inline scale-90">-->
                    <!--                            <font-awesome-icon icon="fa-solid fa-plus"-->
                    <!--                                               class="scale-150 p-4"/>-->
                    <!--                                        </span>-->
                    <!--                    </div>-->
                </div>
                <div v-else-if="form.type ==='check'">
                    <div v-for="(ansData,index) in form.answer">
                        <div class="inline-block mr-4 mt-12">
                            <input type="checkbox" name="checkAns" id="checkAns" class="checkbox absolute mt-5"
                                   :value="index"
                                   v-model="checkRightAnswer">
                        </div>
                        <div class="inline-block w-3/4">
                            <InputText container-style="input-md"
                                       :label-title="'Answer ' + index"
                                       class="my-0.5"
                                       v-model="ansData.name"/>
                        </div>
                    </div>
                    <div class="mt-12">
                        <!--                    <span @click="checkAnsNumber&#45;&#45;"-->
                        <!--                          class="btn btn-circle float-left inline scale-90">-->
                        <!--                        <font-awesome-icon icon="fa-solid fa-minus"-->
                        <!--                                           class="scale-150 p-4"/>-->
                        <!--                    </span>-->
                        <!--                        <span @click="checkAnsNumber++"-->
                        <!--                              class="btn btn-circle float-left inline scale-90">-->
                        <!--                        <font-awesome-icon icon="fa-solid fa-plus"-->
                        <!--                                           class="scale-150 p-4"/>-->
                        <!--                    </span>-->
                    </div>
                </div>
                <button @click.prevent="back"
                    class="btn btn-accent">Cancel</button>
                <button class="btn btn-primary float-right" type="submit">
                    Update Question
                </button>
            </TitleCard>
<!--            <p>{{ form }}</p>-->
<!--            <p>{{ form.answer[1].right_answer }}</p>-->
<!--            <p>{{checkRightAnswer}}</p>-->
        </form>
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import {onUpdated, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const props = defineProps({
    questionAnswerModal: Object
})

const radioRightAnswer = ref();
const checkRightAnswer = ref([]);

const form = useForm({
    name: props.questionAnswerModal.name,
    type: props.questionAnswerModal.type,
    answer: [],
    radioRightAnswer: radioRightAnswer,
    checkRightAnswer: checkRightAnswer,
})

for (let i = 0; i < props.questionAnswerModal.answers.length; i++) {
    form.answer.push({
        name: props.questionAnswerModal.answers[i].name,
        // right_answer: props.questionAnswerModal.answers[i].right_answer
    })
    if (props.questionAnswerModal.answers[i].right_answer && props.questionAnswerModal.type === 'radio') {
        radioRightAnswer.value = i
    } else if (props.questionAnswerModal.answers[i].right_answer && props.questionAnswerModal.type === 'check') {
        checkRightAnswer.value.push(i)
    }
}

//
// if (form.type === 'radio') {
//     for (let i = 0; i < form.questionAnswer.answers.length; i++) {
//         if (form.questionAnswer.answers[i].right_answer === 1) {
//             rightAns.value = i;
//         }
//     }
// }

const radioAnsNumber = ref(props.questionAnswerModal.answers.length)

const checkAnsNumber = ref(props.questionAnswerModal.answers.length)
const resetAns = () => {
    // radioAnsNumber.value = 1
    // checkAnsNumber.value = 1
    radioRightAnswer.value = null
    checkRightAnswer.value = []
}

const destroyQuestion = () => {
    if (confirm('Are you sure to delete this question?')) {
        router.delete(route('question.destroy', props.questionAnswerModal))
    }
}

const back = () =>{
    window.history.back();
}

</script>

<style scoped>

</style>
