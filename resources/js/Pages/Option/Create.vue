<template>
    <Head title="Option"/>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
            <li class="step">Verify</li>
        </ul>
        <form @submit.prevent="form.post(route('option.store'))">
            <Card title="Grouping Options" class="my-4">
                <p>Choose your options</p>
                <div class="divider"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" value="none" class="radio checked:radio-primary mx-2"
                                   v-model="form.groupMethod"
                                   checked/>
                            <span class="label-text">No Preferences</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" value="gender" class="radio checked:radio-primary mx-2"
                                   v-model="form.groupMethod"/>
                            <span class="label-text">Gender</span>
                        </label>
                    </div>
                </div>
            </Card>
            <Card title="Time Options" class="my-4">
                <p>Jigsaw Session = {{ props.topicModal.max_time_jigsaw }} minutes</p>
                <p>How do you want to divide presentation time?</p>
                <div class="divider"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" class="radio checked:radio-primary mx-2"
                                   value="even" v-model="form.timeMethod" checked/>
                            <span class="label-text">Even ({{evenTime}} min)</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" class="radio checked:radio-primary mx-2"
                                   value="uneven" v-model="form.timeMethod"/>
                            <span class="label-text">Uneven</span>
                        </label>
                    </div>
                    <div v-show="form.timeMethod === 'uneven'">
                        <p>Type in number (represent %)</p>
                        <div v-for="item in props.topicModal.no_of_modules">
                            <InputForm :label-name="'Module ' + item" v-model="form.tm[item]"/>
                        </div>
                    </div>
                </div>
            </Card>
            <Card>
                <button type="submit"
                        :disabled="form.processing"
                        class="btn btn-primary">Add Option
                </button>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import InputForm from "@/Components/inputForm.vue";
import {Head, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

const no_of_modules = parseInt(props.topicModal.no_of_modules);

const props = defineProps({
    topicModal: Object, //Modal
})

const evenTime = props.topicModal.max_time_jigsaw / parseInt(props.topicModal.no_of_modules)

//todo: find a way to push tm with even time
const form = useForm({
    topic_id: props.topicModal.id,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: {},
})

</script>

<style scoped>

</style>
