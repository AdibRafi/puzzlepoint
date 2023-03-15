<template>
    <MainLayout>
        <ul class="steps w-96">
            <li class="step step-primary">Topic</li>
            <li class="step step-primary">Modules</li>
            <li class="step step-primary">Options</li>
            <li class="step step">Verify</li>
        </ul>
        <form @submit.prevent="form.post(route('option.store'))">
            <div class="card w-96 bg-base-100 shadow-xl my-2">
                <div class="card-body">
                    <h1 class="card-title">Grouping Options</h1>
                    <p>Choose your options</p>
                    <div class="divider"></div>
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
                </div>
            </div>
            <div class="card w-96 bg-base-100 shadow-xl my-2">
                <div class="card-body">
                    <h1 class="card-title">Time Options</h1>
                    <p>Jigsaw Session = 60 Minutes</p>
                    <p>How do you want to divide presentation time?</p>
                    <div class="divider"></div>
                    <div class="card-actions flex flex-col">
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <input type="radio" class="radio checked:radio-primary mx-2"
                                       value="even" v-model="form.timeMethod" checked/>
                                <span class="label-text">Even (15 min)</span>
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
                            <div v-for="item in no_of_modules">
                                <InputForm :label-name="'Module ' + item" v-model="form.tm[item]"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-96 bg-base-100 shadow-xl my-2 mb-4">
                <div class="card-body">
                    <div class="card-actions justify-center my-2">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Add Option
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import InputForm from "@/Components/inputForm.vue";
import {useForm} from "@inertiajs/vue3";

const no_of_modules = parseInt(props.topicData.no_of_modules);

const props = defineProps({
    topicData: Object, //Modal
})

const form = useForm({
    topic_id: props.topicData.id,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: {},

})

</script>

<style scoped>

</style>
