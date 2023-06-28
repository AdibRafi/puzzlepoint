<template>
    <Head title="Edit Topic"/>
    <Layout page-title="Edit Topic">
        <TitleCard :title="props.topic.name"
                   top-right-button-label="Destroy"
                   @button-function="destroy">
            <div v-if="formStep === 1">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <InputText label-title="Topic Name" v-model="form.topic.name"/>
                    <InputText label-title="Date" input-type="datetime-local"
                               v-model="form.topic.date_time"/>
                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Number of Modules</span>
                        </label>
                        <select class="select select-bordered"
                                v-model="form.topic.no_of_modules">
                            <option :value="null" disabled selected>Pick Number</option>
                            <option :value="2">2 Modules</option>
                            <option :value="3">3 Modules</option>
                            <option :value="4">4 Modules</option>
                            <option :value="5">5 Modules</option>
                            <option :value="6">6 Modules</option>
                        </select>
                    </div>
                </div>
            </div>
            <div v-else-if="formStep === 2">
                <h2 class="card-title mb-8">Grouping Option</h2>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="random" name="groupOption" value="random"
                               v-model="form.option.groupMethod" class="hidden peer" required>
                        <label for="random"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Random</p>
                     <p class="w-full">Just a normal randomise group formation</p>
                  </span>
                            <font-awesome-icon icon="fa-solid fa-shuffle" size="xl"/>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="genderFixed" name="groupOption" value="genderFixed"
                               v-model="form.option.groupMethod"
                               class="hidden peer">
                        <label for="genderFixed"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Gender</div>
                                <div class="w-full">Group formation based on gender</div>
                            </div>
                            <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
                        </label>
                    </li>
                </ul>
                <div class="divider"/>
                <h2 class="card-title mb-8">Time Option for Jigsaw Session</h2>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="even" name="timeOption" value="even"
                               v-model="form.option.timeMethod" class="hidden peer"
                               @click="evenTimeFunction">
                        <label for="even"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Even</p>
                     <p class="w-full">Each Student will present with even minutes</p>
                  </span>
                            <font-awesome-icon icon="fa-solid fa-equals" size="xl"/>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="uneven" name="timeOption" value="uneven"
                               v-model="form.option.timeMethod" class="hidden peer"
                               @click="unevenTimeFunction">
                        <label for="uneven"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Uneven</div>
                                <div class="w-full">Each student will present based on percentage of minutes</div>
                            </div>
                            <font-awesome-icon icon="fa-solid fa-not-equal" size="xl"/>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="divider"/>
            <div v-if="errors"
                 class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mb-4">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                        <p>{{ error.valueOf() }}</p>
                    </div>
                </div>
            </div>
            <button @click.prevent="back" class="btn btn-accent mx-2">Cancel</button>
            <button @click.prevent="duplicateTopic" class="btn btn-primary">Duplicate Topic</button>
            <button type="submit" @click.prevent="nextStep"
                    class="btn btn-primary float-right mx-2">Proceed
            </button>
        </TitleCard>
    </Layout>
</template>
<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";
import {reactive, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    topic: Object,
    option: Object,
    errors: Object,
})

const topic = reactive(props.topic);
const option = reactive(props.option)

const form = useForm({
    topic: topic,
    option: option,
})

const formStep = ref(1);

const back = () => {
    window.history.back()
}
const destroy = () => {
    if (confirm('Are you sure to delete topic?')) {
        router.delete(route('topic.destroy', props.topic.id));
    } else {
        router.reload();
    }
}
const duplicateTopic = () => {
    if (confirm('Are you sure to Duplicate Topic?')) {
        router.post(route('topic.duplicate',
            {
                topic_id: props.topic.id
            }
        ))
    } else {
        router.reload();
    }
}


const nextStep = () => {
    if (formStep.value === 1) {
        const date = new Date(form.topic.date_time);
        const tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
        form.topic.date_time = (new Date(date.getTime() - tzoffset)).toISOString().slice(0, 16);
        console.log(form.topic.date_time)
        router.post(route('topic.edit.validate.step'), {
            steps: 1,
            name: form.topic.name,
            date_time: form.topic.date_time,
            no_of_modules: form.topic.no_of_modules,
        }, {
            onSuccess: () => {
                formStep.value++;
                console.log(formStep.value)
            }
        })
    }
}
</script>
