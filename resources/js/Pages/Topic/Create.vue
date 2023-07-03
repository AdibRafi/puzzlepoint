<template>
    <Head title="Add Topic"/>
    <Layout page-title="Create Topic">
        <ul class="steps w-full">
            <li class="step step-primary">Name, Date & Number of Modules</li>
            <li :class="'step ' + (formStep >= 2 ? 'step-primary':'')">Modules Detail</li>
            <li :class="'step ' + (formStep >= 3 ? 'step-primary':'')">Option</li>
            <li :class="'step ' + (formStep >= 4 ? 'step-primary':'')">Time Session</li>
            <li :class="'step ' + (formStep >= 5 ? 'step-primary':'')">Overview</li>
        </ul>
        <div class="alert alert-info shadow-lg my-10" v-if="!isWizardComplete">
            <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
            <span>Please do the following</span>
        </div>
        <TitleCard title="Add Topic">
            <div v-if="formStep === 1">
                <div v-if="!isWizardComplete">
                    <h2 class="card-title">Tutorial</h2>
                    <p>For this tutorial, there will be an already assign data.</p>
                    <p>You can put any name on this topic, we suggest Mathematics.</p>
                </div>
                <GridLayout>
                    <InputText label-title="Topic Name" v-model="form.topic.name"/>
                    <InputText label-title="Date" input-type="datetime-local"
                               v-model="form.topic.date_time"/>
                </GridLayout>
                <div v-if="!isWizardComplete">
                    <br/>
                    <h2 class="card-title">What is Module?</h2>
                    <p>It is a day lesson (topic) that has been parted into chunks.</p>
                    <br/>
                    <h2 class="card-title">Example</h2>
                    <p>Topic = Mathematics</p>
                    <div class="grid mt-2 lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                        <div v-for="data in dummyData.modules">
                            <div class="stats shadow bg-base-200 w-full">
                                <div class="stat">
                                    <div class="stat-value text-lg">{{ data }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <p>Based on example there will be <span class="font-semibold">4</span> Modules.</p>
                    <br/>
                    <p>Just like the example we will be using 4 modules for now.</p>
                    <br/>
                </div>
                <div class="form-control w-full max-w-xs">
                    <label class="label">
                        <span class="label-text">Number of Modules</span>
                    </label>
                    <select v-if="!isWizardComplete"
                            class="select select-bordered"
                            v-model="form.topic.no_of_modules">
                        <option :value="null" disabled selected>Pick Number of Modules</option>
                        <option :value="4">4 Modules</option>
                    </select>
                    <select v-else-if="isWizardComplete"
                            class="select select-bordered"
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
            <div v-else-if="formStep === 2">
                <GridLayout>
                    <div v-if="!isWizardComplete">
                        <p>Here you may able to put in the detail of the modules</p>
                        <br/>
                        <p>You may edit, but we suggest use the example data.</p>
                        <br/>
                    </div>
                    <div v-for="(moduleData, Index) in form.modules" class="border-2 p-4">
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
                <div v-if="!isWizardComplete">
                    <h2 class="card-title">Tutorial</h2>
                    <p>We suggest choose Random option for Grouping Option</p>
                    <br/>
                </div>
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
                <div v-if="!isWizardComplete">
                    <h2 class="card-title">Tutorial</h2>
                    <p>We suggest choose even option for time option</p>
                    <br/>
                </div>
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
                <div v-if="!isWizardComplete">
                    <h2 class="card-title">What is Expert Session?</h2>
                    <p>It is the first activity where students will discuss within the group based on the module.</p>
                    <br/>
                    <h2 class="card-title">Example Expert Session</h2>
                    <p>Every group will be given a duration for them
                        <!--                        <span class="font-semibold">30 minutes</span>-->
                        to read through and discuss on the module given.</p>
                    <div class="grid mt-2 lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                        <div v-for="(data,index) in form.modules">
                            <div class="stats shadow bg-base-200 w-full">
                                <div class="stat bg-base-200 w-full">
                                    <div class="stat-value text-lg mb-4">Expert Group {{ index + 1 }}</div>
                                    <p>Module: {{ data.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <h2 class="card-title">What is Jigsaw Session?</h2>
                    <p>It is the second activity conducted where students will be present their own content (modules)
                        within their group members.</p>
                    <br/>
                    <h2 class="card-title">Example Jigsaw Session</h2>
                    <p>Every group will be given a duration for them
                        <!--                        <span class="font-semibold">60</span>-->
                        to present within their group.</p>
                    <div class="grid mt-2 lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                        <div v-for="data in 4">
                            <div class="stats shadow bg-base-200 w-full">
                                <div class="stat bg-base-200 w-full">
                                    <div class="stat-value text-lg mb-4">Jigsaw Group {{ data }}</div>
                                    <div v-for="data in form.modules">
                                        <div class="stat border-2">
                                            <div class="stat-value text-sm">{{ data.name }} Student</div>
                                            <!--                                            <div class="stat-desc">Present for 15 minutes</div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <h2 class="card-title">How many time should you put?</h2>
                    <p>We build a system where we can suggest the time for you.</p>
                    <p>According to MMU standard, the maximum time for each class is 120 minutes.</p>
                    <p>And realistically, student will come a bit late, so let say we have 30 minute buffer time.</p>
                    <br/>
                    <p>Then we suggest of doing...</p>
                    <div class="grid mt-2 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
                        <div class="stat w-full border-2">
                            <div class="stat-title">Expert Session</div>
                            <div class="stat-value">29 Minutes</div>
                            <div class="stat-desc">Duration for the expert session</div>
                        </div>
                        <div class="stat w-full border-2">
                            <div class="stat-title">Jigsaw Session</div>
                            <div class="stat-value">58 Minutes</div>
                            <div class="stat-desc">Duration for the jigsaw session</div>
                        </div>
                        <div class="stat w-full border-2">
                            <div class="stat-title">Student present in Jigsaw Session</div>
                            <div class="stat-value">15 Minutes</div>
                            <div class="stat-desc">Since there are 4 modules, <br/> it will divided evenly with jigsaw
                                session time
                            </div>
                        </div>
                        <div class="stat w-full border-2">
                            <div class="stat-title">Transition Time</div>
                            <div class="stat-value">2 Minutes</div>
                            <div class="stat-desc">Duration before expert and jigsaw session</div>
                        </div>
                    </div>
                    <div class="divider"/>
                    <p>In the case of choosing <span class="font-semibold">UNEVEN</span> time option, you will be given
                        a selection of how long for each module that student would present.</p>
                    <p>Each Module will be given a time indicating on how long does students with a given modules should
                        present.</p>
                    <br/>
                    <h2 class="card-title">Example Uneven Option</h2>
                    <p>Try it!</p>
                    <TimeCalculator :number-of-modules="form.topic.no_of_modules"
                                    :number-of-students="props.classroomModal.users_count"
                                    time-method="uneven"
                                    :modules-data="form.modules"/>
                    <div class="divider">End Time Session Tutorial</div>
                    <p>Generate Time and Click Proceed to Continue</p>
                </div>
                <TimeCalculator :number-of-modules="form.topic.no_of_modules"
                                :number-of-students="props.classroomModal.users_count"
                                :time-method="form.option.time_method"
                                :modules-data="form.modules"
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
                    desc="Duration for student to Present"/>
                    <iframe v-if="moduleData.file_path !== ''"
                            :src="'../../../../modules/' + moduleData.file_path.name" type="application/pdf"
                            width="100%" height="800"/>
                </div>
            </div>
            <div class="divider"/>
            <GridLayout>
                <div v-for="error in errors" class="alert alert-error w-full shadow-lg mb-4">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                        <p>{{ error.valueOf() }}</p>
                    </div>
                </div>
            </GridLayout>
            <button @click.prevent="window.history.back()" class="btn btn-accent mx-2">Cancel</button>
            <button v-if="formStep !== 5" type="submit" @click.prevent="nextStep"
                    class="btn btn-primary float-right mx-2">Proceed
            </button>
            <button v-else type="submit" @click.prevent="submitTopic"
                    class="btn btn-primary float-right mx-2">Add Topic
            </button>
        </TitleCard>
    </Layout>
</template>

<script setup>
import Card from "@/Components/Card.vue";
import {reactive, ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {Head, router, usePage} from "@inertiajs/vue3";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import TimeCalculator from "@/Components/TimeCalculator.vue";
import GridLayout from "@/Components/GridLayout.vue";
import Stat from "@/Components/Stat.vue";


const formStep = ref(1);
const evenTime = ref(0);

const displayTime = ref(null);
const wizardStatus = usePage().props.auth.user.wizard_status
const isWizardComplete = usePage().props.auth.user.is_wizard_complete

const props = defineProps({
    classroomModal: Object,
    errors: Object,
    studentModal: Object,

})

const topic = reactive({
    name: '',
    date_time: '',
    no_of_modules: '',
    max_session: '',
    max_buffer: '',
    max_time_expert: '',
    max_time_jigsaw: '',
    transition_time: '',
})

const modulesNew = reactive([])

const tm = ref();

const option = reactive({
    group_distribution: 'random',
    time_method: 'even',
    tm: tm
})


const form = useForm({
    classroom_id: props.classroomModal.id,
    topic: topic,
    modules: modulesNew,
    option: option,
})
const dummyData = {
    'name': 'Mathematics',
    'no_of_modules': 4,
    'modules': ['Addition', 'Subtraction', 'Multiplication', 'Division'],
    'max_expert_time': 29,
    'max_jigsaw_time': 58,
}
if (!usePage().props.auth.user.is_wizard_complete) {
    form.topic.name = dummyData.name;
    form.topic.no_of_modules = dummyData.no_of_modules;
    form.topic.max_time_expert = dummyData.max_expert_time;
    form.topic.max_time_jigsaw = dummyData.max_jigsaw_time;
    for (let i = 0; i < form.topic.no_of_modules; i++) {
        modulesNew.push({
            name: dummyData.modules[i],
            learning_objectives: '',
            file_path: '',
        })
    }
}
const formatDate = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    const strTime = hours + ':' + minutes + ' ' + ampm;
    return (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;

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
    // for (let i = 0; i < 6; i++) {
    // }
}

const unevenTimeFunction = () => {
    tm[1] = 0
    tm[2] = 0
    tm[3] = 0
    tm[4] = 0
    tm[5] = 0
    tm[6] = 0
};

const evenTimeFunction = () => {
    tm[1] = evenTime.value
    tm[2] = evenTime.value
    tm[3] = evenTime.value
    tm[4] = evenTime.value
    tm[5] = evenTime.value
    tm[6] = evenTime.value
}
const submitTopic = () => {
    router.post(route('topic.store'), form)
}

const nextStep = () => {
    if (formStep.value === 1) {
        //todo: validate date_time
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
                if (isWizardComplete) {
                    for (let i = 0; i < form.topic.no_of_modules; i++) {
                        form.modules.push({
                            name: '',
                            learning_objectives: '',
                            file_path: '',
                        });
                    }
                }
            }

        })
    } else if (formStep.value === 2) {
        // form.modules = modulesNew
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
                form.option.tm1 = tm[0]
                form.option.tm2 = tm[1]
                form.option.tm3 = tm[2]
                form.option.tm4 = tm[3]
                form.option.tm5 = tm[4]
                form.option.tm6 = tm[5]
            }
        })
    }
    // if (wizardStatus === 'onCreateTopicsss') {
    //     if (formStep.value === 1) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 1,
    //             name: form.topic.name,
    //             date_time: form.topic.date_time
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //             }
    //         })
    //     } else if (formStep.value === 2) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 2,
    //             no_of_modules: form.topic.no_of_modules,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //                 for (let i = 0; i < form.topic.no_of_modules; i++) {
    //                     modules.push({
    //                         name: dummyData.modules[i],
    //                         learning_objectives: '',
    //                         file_path: '',
    //                     })
    //                 }
    //             }
    //         })
    //     } else if (formStep.value === 3) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 3,
    //             modules: form.modules,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //             }
    //         })
    //     } else if (formStep.value === 4) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 4,
    //             option: form.option,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //             }
    //         })
    //     } else if (formStep.value === 5) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 5,
    //             max_session: form.topic.max_session,
    //             max_buffer: form.topic.max_buffer,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //                 displayTime.value = formatDate(new Date(form.topic.date_time))
    //             }
    //         })
    //     } else if (formStep.value === 6) {
    //         router.post(route('topic.wizard.step'), {
    //             steps: 6,
    //             date_time: form.topic.date_time,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //             }
    //         })
    //     }
    // }
    // else {
    //     if (formStep.value === 1) {
    //         router.post(route('topic.first.step'), {
    //             name: form.topic.name,
    //             date_time: form.topic.date_time,
    //             no_of_modules: form.topic.no_of_modules,
    //             max_time_expert: form.topic.max_time_expert,
    //             max_time_jigsaw: form.topic.max_time_jigsaw,
    //             transition_time: form.topic.transition_time,
    //
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++;
    //                 for (let i = 0; i < form.topic.no_of_modules; i++) {
    //                     modules.push({
    //                         name: '',
    //                         learning_objectives: '',
    //                         file_path: ''
    //                     })
    //                 }
    //             }
    //         });
    //     } else if (formStep.value === 2) {
    //         router.post(route('topic.second.step'), {
    //             modules,
    //         }, {
    //             onSuccess: () => {
    //                 formStep.value++
    //                 evenTime.value = topic.max_time_jigsaw / parseInt(topic.no_of_modules)
    //                 evenTimeFunction();
    //             }
    //         });
    //     }
    // }
}
</script>
