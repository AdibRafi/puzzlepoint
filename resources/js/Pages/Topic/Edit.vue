<template>
    <Head title="Edit Topic"/>
    <Layout page-title="Edit Topic">
        <TitleCard :title="props.topic.name"
                   top-right-button-label="Destroy"
                   @button-function="destroy">
            <form @submit.prevent="form.post(route('topic.update.post'))" method="POST">
                <input type="hidden" name="_method" value="PUT">
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
                                <option :value="null" disabled selected>Pick Number of Modules</option>
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
                    <GridLayout>
                        <div v-for="(moduleData, Index) in modulesNew" class="border-2 p-4">
                            <InputText :label-title="'Module ' + (Index+1)+ ' Name'" input-type="text"
                                       v-model="moduleData.name"/>
                            <!--                        <InputText label-title="Learning Objectives (optional)" input-type="text"-->
                            <!--                                   v-model="moduleData.learning_objectives"/>-->
                            <input type="file"
                                   class="file-input file-input-bordered
                                      file-input-primary mt-4 w-full"
                                   @input="moduleData.file_path = $event.target.files[0]"/>
                        </div>
                    </GridLayout>
                </div>
                <div v-else-if="formStep === 3">
                    <h2 class="card-title mb-8">Grouping Option</h2>
                    <ul class="grid w-full gap-6 md:grid-cols-2">
                        <li>
                            <input type="radio" id="random" name="groupOption" value="random"
                                   v-model="form.option.group_distribution" class="hidden peer" required>
                            <label for="random"
                                   class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Random</p>
                     <p class="w-full">Normal Randomise Students into Groups</p>
                  </span>
                                <font-awesome-icon icon="fa-solid fa-shuffle" size="xl"/>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="genderFixed" name="groupOption" value="genderFixed"
                                   v-model="form.option.group_distribution"
                                   class="hidden peer">
                            <label for="genderFixed"
                                   class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">Fixed Gender</div>
                                    <div class="w-full">Group formation based on gender</div>
                                </div>
                                <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="genderMixed" name="groupOption" value="genderMixed"
                                   v-model="form.option.group_distribution"
                                   class="hidden peer">
                            <label for="genderMixed"
                                   class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                                <div class="block">
                                    <div class="w-full text-lg font-semibold">Mixed Gender</div>
                                    <div class="w-full">Mixed Gender Group Formation</div>
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
                                   v-model="form.option.time_method" class="hidden peer">
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
                                   v-model="form.option.time_method" class="hidden peer">
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
                <div v-else-if="formStep === 4">
                    <TimeCalculator :number-of-modules="form.topic.no_of_modules"
                                    :number-of-students="props.classroom.users_count"
                                    :modules-data="form.modules"
                                    :time-method="form.option.time_method"
                                    @out-data="getTime"/>
                </div>
                <div v-else-if="formStep === 5">
                    <GridLayout>
                        <Stat title="Topic Name" :value="form.topic.name"/>
                        <Stat title="Date and Time" :value="displayTime"/>
                        <Stat title="Number of Modules" :value="form.topic.no_of_modules"/>
                        <Stat title="Group Method" :value="form.option.group_distribution"/>
                        <Stat title="Time Method for Jigsaw Session" :value="form.option.time_method"/>
                    </GridLayout>
                    <div class="divider">Module</div>
                    <GridLayout>
                        <div v-for="(moduleData,index) in form.modules">
                            <Stat :title="'Module' + (index+1)" :value="moduleData.name"/>
                        </div>
                    </GridLayout>
                    <div class="divider">Time Session</div>
                    <GridLayout>
                        <Stat title="Total Time" :value="form.topic.max_session + ' Minutes'"/>
                        <Stat title="Buffer time" :value="form.topic.max_buffer + ' Minutes'"/>
                        <Stat title="Expert Session" :value="form.topic.max_time_expert + ' Minutes'"/>
                        <Stat title="Jigsaw Session" :value="form.topic.max_time_jigsaw + ' Minutes'"/>
                        <Stat title="Transition Time" :value="form.topic.transition_time + ' Minutes'"/>
                    </GridLayout>
                    <div class="divider">Module Time and File Detail</div>
                    <div v-for="(moduleData,index) in form.modules" :key="moduleData">
                        <Stat :title="moduleData.name"
                              :value="form.option.tm[index] + ' Minutes'"
                              class="my-4">
                            <iframe v-if="moduleData.file_path !== ''"
                                    :src="'../../../../modules/' + moduleData.file_path.name" type="application/pdf"
                                    width="100%" height="800"/>
                        </Stat>
                    </div>
                </div>
                <div class="divider"/>

                <div v-if="errors"
                     class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="error in errors"
                         class="alert alert-error w-full shadow-lg mb-4">
                        <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                        <p>{{ error.valueOf() }}</p>
                    </div>
                </div>
                <button @click.prevent="back"
                        class="btn btn-accent mx-2">Cancel
                </button>
                <button v-if="formStep >= 2"
                        @click.prevent="previousStep"
                        class="btn btn-primary">Previous
                </button>
                <button v-if="formStep !== 5" type="submit" @click.prevent="nextStep"
                        class="btn btn-primary float-right mx-2">Proceed
                </button>
                <button v-else type="submit"
                        class="btn btn-primary float-right mx-2">Update Topic
                </button>
                <button @click.prevent="duplicateTopic"
                        class="btn btn-primary float-right ">Duplicate Topic
                </button>
            </form>
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
import GridLayout from "@/Components/GridLayout.vue";
import TimeCalculator from "@/Components/TimeCalculator.vue";
import Stat from "@/Components/Stat.vue";

const props = defineProps({
    topic: Object,
    option: Object,
    modules: Object,
    classroom: Object,
    errors: Object,
})

const tm = ref()

const topic = reactive(props.topic);
const option = reactive({
    group_distribution: props.option.group_distribution,
    time_method: props.option.time_method,
    tm: tm
})
const modules = reactive(props.modules)
const modulesNew = reactive([]);


const displayTime = ref(null);
const formatDate = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    const strTime = hours + ':' + minutes + ' ' + ampm;
    return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear() + "  " + strTime;
}

const form = useForm({
    topic: topic,
    option: option,
    modules: modules,
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

const getTime = (value) => {
    console.log(value)
    form.topic.max_session = value.outSession;
    form.topic.max_buffer = value.outBuffer;
    form.topic.max_time_expert = value.outExpert;
    form.topic.max_time_jigsaw = value.outJigsaw;
    form.topic.transition_time = value.outTransition;
    tm.value = value.outStudentPresent;
    form.option.time_method = value.outTimeMethod;

}
const previousStep = () => {
    formStep.value--;
}

const nextStep = () => {
    if (formStep.value === 1) {
        if (form.topic.date_time === '') {
            return null;
        }
        const date = new Date(form.topic.date_time);
        const tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
        form.topic.date_time = (new Date(date.getTime() - tzoffset)).toISOString().slice(0, 16);
        router.post(route('topic.validate.step'), {
            steps: 1,
            name: form.topic.name,
            date_time: form.topic.date_time,
            no_of_modules: form.topic.no_of_modules,
        }, {
            onSuccess: () => {
                formStep.value++;
                modulesNew.splice(0)
                for (let i = 0; i < form.topic.no_of_modules; i++) {
                    if (form.modules[i] !== undefined) {
                        modulesNew.push({
                            name: form.modules[i].name,
                            learning_objectives: form.modules[i].learning_objectives,
                            file_path: '',
                        })
                    } else {
                        modulesNew.push({
                            name: '',
                            learning_objectives: '',
                            file_path: ''
                        })
                    }
                }
            }
        })
    } else if (formStep.value === 2) {
        form.modules = modulesNew
        for (let i = 0; i < form.modules.length; i++) {
            if (form.modules[i].name === '') {
                return null
            }
        }
        router.post(route('topic.validate.step'), {
            steps: 2,
            modules: form.modules,
        })
        formStep.value++
    } else if (formStep.value === 3) {
        router.post(route('topic.validate.step'), {
            steps: 3,
            group_distribution: form.option.group_distribution,
            time_method: form.option.time_method,

        }, {
            onSuccess: () => {
                formStep.value++;
            }
        });
    } else if (formStep.value === 4) {
        router.post(route('topic.validate.step'), {
            steps: 4,
            max_session: form.topic.max_session,
            max_buffer: form.topic.max_buffer,
        }, {
            onSuccess: () => {
                formStep.value++;
                displayTime.value = formatDate(new Date(form.topic.date_time))
                // form.option.tm1 = tm[0]
                // form.option.tm2 = tm[1]
                // form.option.tm3 = tm[2]
                // form.option.tm4 = tm[3]
                // form.option.tm5 = tm[4]
                // form.option.tm6 = tm[5]
            }
        })
    }
}
</script>
