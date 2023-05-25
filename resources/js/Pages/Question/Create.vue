<template>
    <Head title="Question"/>
    <Layout>
        <TitleCard title="Add Question">

        </TitleCard>
        <form @submit.prevent="form.post(route('question.store'))">
            <Card title="DEVELOPER">
                <p>q type = {{ form.type }}</p>
                <p>radioNum = {{ radioAnsNumber }}</p>
                <p>checkNum = {{ checkAnsNumber }}</p>
                <p>q name = {{ form.name }}</p>
                <p>ans name ={{ form.answer.name }}</p>
                <p>ans rightAns = {{ form.answer.right_answer }}</p>
            </Card>
            <Card title="Question Type">
                <label class="label cursor-pointer">
                    <span class="label-text">One Answer</span>
                    <input type="radio" name="AnsType" id="radioAnsType"
                           value="radio"
                           class="radio checked:radio-secondary"
                           v-model="form.type"
                           @click="resetAns"
                           checked/>
                </label>
                <label class="label cursor-pointer">
                    <span class="label-text">Multiple Answer</span>
                    <input type="radio" name="AnsType" id="radioAnsType"
                           value="check"
                           class="radio checked:radio-secondary"
                           @click="resetAns"
                           v-model="form.type"/>
                </label>
                <!--                        <label class="label cursor-pointer">-->
                <!--                            <span class="label-text">Short answer</span>-->
                <!--                            <input type="radio" name="AnsType" id="radioAnsType"-->
                <!--                                   value="input"-->
                <!--                                   class="radio checked:radio-secondary"-->
                <!--                                   @click="resetAns"-->
                <!--                                   v-model="form.question_type"/>-->
                <!--                        </label>-->
                <!--                        <label class="label cursor-pointer">-->
                <!--                            <span class="label-text">Long answer</span>-->
                <!--                            <input type="radio" name="AnsType" id="radioAnsType"-->
                <!--                                   value="textarea"-->
                <!--                                   class="radio checked:radio-secondary"-->
                <!--                                   @click="resetAns"-->
                <!--                                   v-model="form.question_type"/>-->
                <!--                        </label>-->
            </Card>
            <Card title="Question">
                <InputForm input-placeholder="Type Question Here" v-model="form.name"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <div v-if="form.type ==='radio'">
                            <div v-for="ansItem in radioAnsNumber">
                                <label class="label cursor-pointer">
                                    <input type="radio" name="radioAns" class="mr-2 radio" :value="ansItem"
                                           v-model="form.answer.right_answer">
                                    <input type="text" placeholder="Type your answer here"
                                           class="input input-bordered input-sm w-full max-w-xs"
                                           v-model="form.answer.name[ansItem]">
                                </label>
                            </div>
                            <div class="my-2">
                                        <span @click="radioAnsNumber--"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-minus"
                                                               class="scale-150 p-4"/>
                                        </span>
                                <span @click="radioAnsNumber++"
                                      class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-plus"
                                                               class="scale-150 p-4"/>
                                        </span>
                            </div>
                        </div>
                        <div v-else-if="form.type ==='check'">
                            <div v-for="ansItem in checkAnsNumber">
                                <label class="label cursor-pointer">
                                    <input type="checkbox" name="checkAns" class="mr-2 checkbox"
                                           v-model="form.answer.right_answer[ansItem]">
                                    <input type="text" placeholder="Type your answer here"
                                           class="input input-bordered input-sm w-full max-w-xs"
                                           v-model="form.answer.name[ansItem]">
                                </label>
                            </div>
                            <div class="my-2">
                                        <span @click="checkAnsNumber--"
                                              class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-minus"
                                                               class="scale-150 p-4"/>
                                        </span>
                                <span @click="checkAnsNumber++"
                                      class="btn btn-circle float-left inline scale-90">
                                            <font-awesome-icon icon="fa-solid fa-plus"
                                                               class="scale-150 p-4"/>
                                        </span>
                            </div>
                        </div>
                        <!--                        <div v-else-if="form.question_type === 'input'">-->
                        <!--                            <InputForm label-name="Answer" input-placeholder="Type answer here"-->
                        <!--                                       v-model="form.answer.name[1]"/>-->
                        <!--                        </div>-->
                        <!--                        <div v-else>-->
                        <!--                            <div class="form-control">-->
                        <!--                                <label class="label">-->
                        <!--                                    <span class="label-text">Answer</span>-->
                        <!--                                </label>-->
                        <!--                                <textarea class="textarea textarea-bordered textarea-primary h-24 w-72"-->
                        <!--                                          placeholder="Type Here" v-model="form.answer.name[1]"/>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>
                <template #actions>
                    <button type="submit" :disabled="form.processing" class="btn btn-primary">
                        Add into assessment
                    </button>
                </template>
            </Card>
        </form>
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import {onUpdated, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";

const props = defineProps({
    assessment_id: Object,
})

const form = useForm({
    assessment_id: props.assessment_id,
    name: '',
    type: 'radio',
    answer: {
        name: {},
        right_answer: {}
    }
})
onUpdated(function () {
    form.reset();
})

const radioAnsNumber = ref(1)

const checkAnsNumber = ref(1)
const resetAns = () => {
    radioAnsNumber.value = 1
    checkAnsNumber.value = 1
    form.answer.name = {}
    form.answer.right_answer = {}
}

</script>

<style scoped>

</style>
