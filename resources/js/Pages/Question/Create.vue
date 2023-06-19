<template>
    <Head title="Question"/>
    <Layout>
        <!--        <Card title="DEVELOPER">-->
        <!--            <p>q type = {{ form.type }}</p>-->
        <!--            <p>radioNum = {{ radioAnsNumber }}</p>-->
        <!--            <p>checkNum = {{ checkAnsNumber }}</p>-->
        <!--            <p>q name = {{ form.name }}</p>-->
        <!--            <p>ans name ={{ form.answer.name }}</p>-->
        <!--            <p>ans rightAns = {{ form.answer.right_answer }}</p>-->
        <!--        </Card>-->
        <TitleCard title="Add Question">
            <form @submit.prevent="form.post(route('question.store'))">
                <div v-if="wizardStatus === 'onCreateAssessment'"
                     class="alert alert-info shadow-lg mb-4">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Choose one either you want single or multiple answers</span>
                    </div>
                </div>
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
                <div v-if="wizardStatus === 'onCreateAssessment'"
                     class="alert alert-info shadow-lg mt-4">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Try write any question and answers. <br/>
                        To choose the right answers, tick the right box</span>
                    </div>
                </div>
                <!--todo: fix bug on small area can be click on InputText-->
                <div v-if="form.type ==='radio'">
                    <div v-for="ansItem in radioAnsNumber">
                        <div class="inline-block mr-4 mt-12">
                            <input type="radio" name="radioAns" id="radioAns" class="radio absolute mt-5"
                                   :value="ansItem"
                                   v-model="form.answer.right_answer">
                        </div>
                        <div class="inline-block w-3/4">
                            <InputText container-style="input-md"
                                       :label-title="'Answer ' + ansItem"
                                       class=""
                                       v-model="form.answer.name[ansItem]"/>
                        </div>
                    </div>
                    <div class="mt-16">
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
                        <div class="inline-block mr-4 mt-12">
                            <input type="checkbox" name="checkAns" id="checkAns" class="checkbox absolute mt-5"
                                   :value="ansItem"
                                   v-model="form.answer.right_answer[ansItem]">
                        </div>
                        <div class="inline-block w-3/4">
                            <InputText container-style="input-md"
                                       :label-title="'Answer ' + ansItem"
                                       class="my-0.5"
                                       v-model="form.answer.name[ansItem]"/>
                        </div>
                    </div>
                    <div class="mt-12">
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
                <!--            </div>-->
                <button class="btn btn-primary float-right"
                        :disabled="form.processing"
                        type="submit">
                    Add into Assessment
                </button>
                <div :class="'float-right mx-2 ' +
                (wizardStatus === 'onStartSession' ? 'tooltip tooltip-info tooltip-open tooltip-bottom lg:tooltip-left':'')"
                     data-tip="If you're done, Click here to return to assessment page">
                    <Link v-if="wizardStatus === 'onStartSession' || $page.props.auth.user.is_wizard_complete" :href="route('assessment.index',{
                        assessment_id:props.assessment_id
                    })"
                          class="btn btn-secondary">
                        Back to Assessment
                    </Link>
                </div>
                <div v-if="errors" class="mt-6">
                    <div v-for="error in errors"
                         class="alert alert-error w-full shadow-lg mb-4">
                            <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                            <p>{{ error.valueOf() }}</p>
                    </div>
                </div>
            </form>
        </TitleCard>
        <!--            <Card title="Question Type">-->
        <!--                <label class="label cursor-pointer">-->
        <!--                    <span class="label-text">One Answer</span>-->
        <!--                    <input type="radio" name="AnsType" id="radioAnsType"-->
        <!--                           value="radio"-->
        <!--                           class="radio checked:radio-secondary"-->
        <!--                           v-model="form.type"-->
        <!--                           @click="resetAns"-->
        <!--                           checked/>-->
        <!--                </label>-->
        <!--                <label class="label cursor-pointer">-->
        <!--                    <span class="label-text">Multiple Answer</span>-->
        <!--                    <input type="radio" name="AnsType" id="radioAnsType"-->
        <!--                           value="check"-->
        <!--                           class="radio checked:radio-secondary"-->
        <!--                           @click="resetAns"-->
        <!--                           v-model="form.type"/>-->
        <!--                </label>-->
        <!--                                        <label class="label cursor-pointer">-->
        <!--                                            <span class="label-text">Short answer</span>-->
        <!--                                            <input type="radio" name="AnsType" id="radioAnsType"-->
        <!--                                                   value="input"-->
        <!--                                                   class="radio checked:radio-secondary"-->
        <!--                                                   @click="resetAns"-->
        <!--                                                   v-model="form.question_type"/>-->
        <!--                                        </label>-->
        <!--                                        <label class="label cursor-pointer">-->
        <!--                                            <span class="label-text">Long answer</span>-->
        <!--                                            <input type="radio" name="AnsType" id="radioAnsType"-->
        <!--                                                   value="textarea"-->
        <!--                                                   class="radio checked:radio-secondary"-->
        <!--                                                   @click="resetAns"-->
        <!--                                                   v-model="form.question_type"/>-->
        <!--                                        </label>-->
        <!--            </Card>-->
        <!--            <Card title="Question">-->
        <!--                <InputForm input-placeholder="Type Question Here" v-model="form.name"/>-->
        <!--                <div class="card-actions flex flex-col">-->
        <!--                    <div class="form-control">-->
        <!--                        <div v-if="form.type ==='radio'">-->
        <!--                            <div v-for="ansItem in radioAnsNumber">-->
        <!--                                <label class="label cursor-pointer">-->
        <!--                                    <input type="radio" name="radioAns" class="mr-2 radio" :value="ansItem"-->
        <!--                                           v-model="form.answer.right_answer">-->
        <!--                                    <input type="text" placeholder="Type your answer here"-->
        <!--                                           class="input input-bordered input-sm w-full max-w-xs"-->
        <!--                                           v-model="form.answer.name[ansItem]">-->
        <!--                                </label>-->
        <!--                            </div>-->
        <!--                            <div class="my-2">-->
        <!--                                        <span @click="radioAnsNumber&#45;&#45;"-->
        <!--                                              class="btn btn-circle float-left inline scale-90">-->
        <!--                                            <font-awesome-icon icon="fa-solid fa-minus"-->
        <!--                                                               class="scale-150 p-4"/>-->
        <!--                                        </span>-->
        <!--                                <span @click="radioAnsNumber++"-->
        <!--                                      class="btn btn-circle float-left inline scale-90">-->
        <!--                                            <font-awesome-icon icon="fa-solid fa-plus"-->
        <!--                                                               class="scale-150 p-4"/>-->
        <!--                                        </span>-->
        <!--                            </div>-->
        <!--                        </div>-->
        <!--                        <div v-else-if="form.type ==='check'">-->
        <!--                            <div v-for="ansItem in checkAnsNumber">-->
        <!--                                <label class="label cursor-pointer">-->
        <!--                                    <input type="checkbox" name="checkAns" class="mr-2 checkbox"-->
        <!--                                           v-model="form.answer.right_answer[ansItem]">-->
        <!--                                    <input type="text" placeholder="Type your answer here"-->
        <!--                                           class="input input-bordered input-sm w-full max-w-xs"-->
        <!--                                           v-model="form.answer.name[ansItem]">-->
        <!--                                </label>-->
        <!--                            </div>-->
        <!--                            <div class="my-2">-->
        <!--                                        <span @click="checkAnsNumber&#45;&#45;"-->
        <!--                                              class="btn btn-circle float-left inline scale-90">-->
        <!--                                            <font-awesome-icon icon="fa-solid fa-minus"-->
        <!--                                                               class="scale-150 p-4"/>-->
        <!--                                        </span>-->
        <!--                                <span @click="checkAnsNumber++"-->
        <!--                                      class="btn btn-circle float-left inline scale-90">-->
        <!--                                            <font-awesome-icon icon="fa-solid fa-plus"-->
        <!--                                                               class="scale-150 p-4"/>-->
        <!--                                        </span>-->
        <!--                            </div>-->
        <!--                        </div>-->
        <!--                        &lt;!&ndash;                        <div v-else-if="form.question_type === 'input'">&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            <InputForm label-name="Answer" input-placeholder="Type answer here"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                       v-model="form.answer.name[1]"/>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        </div>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        <div v-else>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            <div class="form-control">&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                <label class="label">&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                    <span class="label-text">Answer</span>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                </label>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                <textarea class="textarea textarea-bordered textarea-primary h-24 w-72"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                          placeholder="Type Here" v-model="form.answer.name[1]"/>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            </div>&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        </div>&ndash;&gt;-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <template #actions>-->
        <!--                    <button type="submit" :disabled="form.processing" class="btn btn-primary">-->
        <!--                        Add into assessment-->
        <!--                    </button>-->
        <!--                </template>-->
        <!--            </Card>-->
    </Layout>
</template>

<script setup>
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import {onMounted, onUpdated, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const back = () => {
    window.history.back();
}

const props = defineProps({
    assessment_id: Object,
    errors: Object,
})

let wizardStatus = usePage().props.auth.user.wizard_status;

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
    wizardStatus = usePage().props.auth.user.wizard_status;
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

onMounted(() => {
    router.reload()
})

</script>

<style scoped>

</style>
