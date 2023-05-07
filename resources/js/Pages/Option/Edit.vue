<template>
    <Head title="Option"/>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
            <li class="step">Verify</li>
        </ul>
        <Card title="DEVELOPER">
            <p>tm = {{form.tm}}</p>
        </Card>
        <form @submit.prevent="form.post(route('option.updateIndex'))">
            <Card title="Grouping Options" class="my-4">
                <p>Choose your options</p>
                <div class="divider"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" value="none" class="radio checked:radio-primary mx-2"
                                   v-model="form.groupMethod"/>
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
                                   value="even" v-model="form.timeMethod"/>
                            <span class="label-text">Even ({{ evenTime }} min)</span>
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
                        <div v-for="(item,index) in props.topicModal.no_of_modules">
                            <InputForm :label-name="'Module ' + item" v-model="form.tm[index]"/>
                        </div>
                    </div>
                </div>
            </Card>
            <Card>
                <button type="submit"
                        :disabled="form.processing"
                        class="btn btn-primary">Edit Option
                </button>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import InputForm from "@/Components/InputForm.vue";
import {Head, useForm} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topicModal: Object,
    optionModal: Object,
})

const no_of_modules = parseInt(props.topicModal.no_of_modules);

const evenTime = props.topicModal.max_time_jigsaw / parseInt(props.topicModal.no_of_modules)

const initiateTm = {
    0: props.optionModal.tm1,
    1: props.optionModal.tm2,
    2: props.optionModal.tm3,
    3: props.optionModal.tm4,
    4: props.optionModal.tm5,
    5: props.optionModal.tm6,
}

//todo: find a way to push tm with even time

const form = useForm({
    topic_id: props.topicModal.id,
    groupMethod: props.optionModal.group_distribution,
    timeMethod: props.optionModal.time_method,
    tm: initiateTm,
})

</script>

<style scoped>

</style>
