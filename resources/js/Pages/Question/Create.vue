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
                        <span @click="radioAnsNumber === 1 ? null : radioAnsNumber--"
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
                    <span @click="checkAnsNumber === 1 ? null : checkAnsNumber--"
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
                <button class="btn btn-primary float-right"
                        :disabled="form.processing"
                        type="submit">
                    Add into Assessment
                </button>
                <div :class="'float-right mx-2 ' +
                (wizardStatus === 'onPublishAssessment' ? 'tooltip tooltip-info tooltip-open tooltip-bottom lg:tooltip-left':'')"
                     data-tip="If you're done, Click here to return to assessment page">
                    <Link v-if="wizardStatus === 'onPublishAssessment' || $page.props.auth.user.is_wizard_complete"
                          :href="route('assessment.index',{
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
    </Layout>
</template>

<script setup>
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import {onMounted, onUpdated, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
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
