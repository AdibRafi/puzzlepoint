<template>
    <Head title="Add Topic"/>
    <Layout page-title="Create Topic">
        <div v-if="$page.props.auth.user.is_wizard_complete">
            <ul class="steps w-full">
                <li class="step step-primary">Topic</li>
                <li :class="'step ' + (formStep >= 2 ? 'step-primary':'')">Modules</li>
                <li :class="'step ' + (formStep === 3 ? 'step-primary':'')">Options</li>
            </ul>
            <TitleCard title="Add Topic" v-if="formStep===1">
                <div v-if="wizardStatus === 'onCreateTopic'"
                     class="alert alert-info shadow-lg mb-10">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span>Fill in the topic, number of modules, max time for jigsaw and expert, and transition time</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <InputText label-title="Topic Name" input-type="text"
                               v-model="topic.name"/>
                    <div :class="wizardStatus === 'onCreateTopic' ?
            'tooltip tooltip-top tooltip-info tooltip-open': ''"
                         data-tip="This will affect the time for jigsaw session">
                        <InputText label-title="Number of Modules"
                                   input-type="number"
                                   v-model="topic.no_of_modules"/>
                    </div>
                    <div class="tooltip tooltip-top tooltip-primary"
                         data-tip="Advisable to put a bit less time">
                        <InputText label-title="Maximum Time for Expert Session"
                                   input-type="number"
                                   v-model="topic.max_time_expert"/>
                    </div>
                    <div class="tooltip tooltip-top tooltip-primary"
                         data-tip="Advisable to put a bit less time">
                        <InputText label-title="Maximum Time for Jigsaw Session"
                                   input-type="number"
                                   v-model="topic.max_time_jigsaw"/>
                    </div>
                    <div class="tooltip tooltip-bottom tooltip-info"
                         data-tip="2 - 5 Minutes">
                        <InputText label-title="Transition Time" input-type="number"
                                   v-model="topic.transition_time"/>
                    </div>
                    <InputText label-title="Date" input-type="datetime-local"
                               v-model="topic.date_time"/>
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
                <div class="mt-10">
                    <button @click.prevent="nextStep"
                            class="btn btn-primary float-right">
                        Proceed
                    </button>
                    <!-- The button to open modal -->
                    <label for="my_modal_6" class="btn">open modal</label>

                    <!-- Put this part before </body> tag -->
                    <input type="checkbox" id="my_modal_6" class="modal-toggle"/>
                    <div class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg">Hello!</h3>
                            <p class="py-4">This modal works with a hidden checkbox!</p>
                            <div class="modal-action">
                                <label for="my_modal_6" class="btn">Close!</label>
                            </div>
                        </div>
                    </div>
                </div>
            </TitleCard>
            <TitleCard title="Add Modules" v-if="formStep===2">
                <div v-if="wizardStatus === 'onCreateTopic'"
                     class="alert alert-info shadow-lg mb-10">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span>
                  Fill in the name in each modules. <br/>
                  (Don't worry about the learning_objectives and file for now)
               </span>
                    </div>
                </div>
                <div v-for="(moduleData,Index) in modules" :key="moduleData">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <InputText :label-title="'Module ' + (Index+1)+ ' Name'" input-type="text"
                                   v-model="moduleData.name"/>
                        <InputText label-title="Learning Objectives (optional)" input-type="text"
                                   v-model="moduleData.learning_objectives"/>
                        <input type="file"
                               class="file-input file-input-bordered
                                      file-input-primary mt-4"
                               @input="module.file_path = $event.target.files[0]"/>
                    </div>
                    <div class="divider"/>
                </div>
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
                <div class="mt-10">
                    <button @click.prevent="nextStep"
                            class="btn btn-primary float-right">
                        Proceed
                    </button>
                </div>
            </TitleCard>
            <TitleCard title="Add Option" v-if="formStep===3">
                <div v-if="wizardStatus === 'onCreateTopic'"
                     class="alert alert-info shadow-lg mb-10">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span>
                  Choose the following option for both grouping and time option
               </span>
                    </div>
                </div>
                <h2 class="card-title mb-8">Grouping Option</h2>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="random" name="groupOption" value="random"
                               v-model="option.groupMethod" class="hidden peer" required>
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
                               v-model="option.groupMethod"
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
                               v-model="option.timeMethod" class="hidden peer"
                               @click="evenTimeFunction">
                        <label for="even"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Even</p>
                     <p class="w-full">Each Student will present for {{ evenTime }} minutes</p>
                  </span>
                            <font-awesome-icon icon="fa-solid fa-equals" size="xl"/>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="uneven" name="timeOption" value="uneven"
                               v-model="option.timeMethod" class="hidden peer"
                               @click="unevenTimeFunction">
                        <label for="uneven"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Uneven</div>
                                <div class="w-full">Fill in the specify minutes below</div>
                            </div>
                            <font-awesome-icon icon="fa-solid fa-not-equal" size="xl"/>
                        </label>
                    </li>
                </ul>
                <div v-if="option.timeMethod === 'uneven'">
                    <div class="divider"/>
                    <div v-for="(moduleData,index) in modules" :key="moduleData">
                        <InputText :label-title="'Time for Module ' + (index+1)" input-type="number"
                                   v-model="option.tm[index+1]"/>
                    </div>
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
                <div class="mt-10">
                    <button @click.prevent="submit"
                            :disabled="form.processing"
                            class="btn btn-primary float-right">
                        Submit
                    </button>
                </div>
            </TitleCard>
        </div>
        <div v-else>
            <div class="alert alert-info shadow-lg my-10">
                <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                <span>Please do the following</span>
            </div>
            <ul class="steps w-full">
                <li class="step step-primary">Name and Date</li>
                <li :class="'step ' + (formStep >= 2 ? 'step-primary':'')">Modules</li>
                <li :class="'step ' + (formStep >= 3 ? 'step-primary':'')">Modules Detail</li>
                <li :class="'step ' + (formStep >= 4 ? 'step-primary':'')">Option</li>
                <li :class="'step ' + (formStep >= 5 ? 'step-primary':'')">Time Session</li>
                <li :class="'step ' + (formStep >= 6 ? 'step-primary':'')">Overview</li>
            </ul>
            <TitleCard title="Name and Date" v-if="formStep === 1">
                <h2 class="card-title">Tutorial</h2>
                <p>For this tutorial, there will be an already assign data.</p>
                <br/>
                <p>You can put any name on this topic, we suggest mathematics.</p>
                <br/>
                <InputText label-title="Topic Name"
                           v-model="form.topic.name"/>
                <InputText label-title="Date and Time"
                           v-model="form.topic.date_time"
                           input-type="datetime-local"/>
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="nextStep"
                        class="btn btn-primary float-right mt-10">
                    Proceed
                </button>
            </TitleCard>
            <TitleCard title="Modules" v-if="formStep === 2">
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
                <div class="form-control w-full max-w-xs">
                    <label class="label">
                        <span class="label-text">No of modules</span>
                    </label>
                    <select class="select select-bordered"
                            v-model="form.topic.no_of_modules">
                        <option :value="null" disabled selected>No of Modules</option>
                        <option :value="4">4 Modules</option>
                    </select>
                </div>
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="nextStep"
                        class="btn btn-primary float-right mt-10">
                    Proceed
                </button>
            </TitleCard>
            <TitleCard title="Modules Detail" v-if="formStep === 3">
                <h2 class="card-title">Tutorial</h2>
                <p>Here you may able to put in the detail of the modules</p>
                <br/>
                <p>You may edit, but we suggest use the example data.</p>
                <br/>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="(moduleData, Index) in form.modules" class="border-2 p-4">
                        <InputText :label-title="'Module ' + (Index+1)+ ' Name'" input-type="text"
                                   v-model="moduleData.name"/>
                        <InputText label-title="Learning Objectives (optional)" input-type="text"
                                   v-model="moduleData.learning_objectives"/>
                        <input type="file"
                               class="file-input file-input-bordered
                                      file-input-primary mt-4 w-full"
                               @input="module.file_path = $event.target.files[0]"/>
                    </div>
                </div>
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="nextStep"
                        class="btn btn-primary float-right mt-10">
                    Proceed
                </button>
            </TitleCard>
            <TitleCard title="Option" v-if="formStep === 4">
                <h2 class="card-title">Tutorial</h2>
                <p>We suggest choose random option for grouping option</p>
                <br/>
                <h2 class="card-title mb-8">Grouping Option</h2>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="random" name="groupOption" value="random"
                               v-model="option.groupMethod" class="hidden peer" required>
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
                               v-model="option.groupMethod"
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
                <h2 class="card-title">Tutorial</h2>
                <p>We suggest choose even option for time option</p>
                <br/>
                <h2 class="card-title mb-8">Time Option for Jigsaw Session</h2>
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    <li>
                        <input type="radio" id="even" name="timeOption" value="even"
                               v-model="option.timeMethod" class="hidden peer"
                               @click="evenTimeFunction">
                        <label for="even"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Even</p>
                     <p class="w-full">Each Student will present for {{ evenTime }} minutes</p>
                  </span>
                            <font-awesome-icon icon="fa-solid fa-equals" size="xl"/>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="uneven" name="timeOption" value="uneven"
                               v-model="option.timeMethod" class="hidden peer"
                               @click="unevenTimeFunction">
                        <label for="uneven"
                               class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Uneven</div>
                                <div class="w-full">Fill in the specify minutes below</div>
                            </div>
                            <font-awesome-icon icon="fa-solid fa-not-equal" size="xl"/>
                        </label>
                    </li>
                </ul>
                <div v-if="option.timeMethod === 'uneven'">
                    <div class="divider"/>
                    <div v-for="(moduleData,index) in modules" :key="moduleData">
                        <InputText :label-title="'Time for Module ' + (index+1)" input-type="number"
                                   v-model="option.tm[index+1]"/>
                    </div>
                </div>
                <div class="divider"/>
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="nextStep"
                        class="btn btn-primary float-right mt-10">
                    Proceed
                </button>
            </TitleCard>
            <TitleCard title="Time Session" v-if="formStep === 5">
                <h2 class="card-title">What is Expert Session?</h2>
                <p>It is the first activity where students will discuss within the group based on the module.</p>
                <br/>
                <h2 class="card-title">Example Expert Session</h2>
                <p>Every group will be given <span class="font-semibold">20</span> minutes to read through and discuss
                    on the module given.</p>
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
                <p>It is the second activity conducted where students will be present their own content (modules) within
                    their
                    group members.</p>
                <br/>
                <h2 class="card-title">Example Jigsaw Session</h2>
                <p>Every group will be given of <span class="font-semibold">60</span> minutes to present within their
                    group.</p>
                <div class="grid mt-2 lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                    <div v-for="data in 4">
                        <div class="stats shadow bg-base-200 w-full">
                            <div class="stat bg-base-200 w-full">
                                <div class="stat-value text-lg mb-4">Jigsaw Group {{ data }}</div>
                                <div v-for="data in form.modules">
                                    <div class="stat border-2">
                                        <div class="stat-value text-sm">{{ data.name }} Student</div>
                                        <div class="stat-desc">Present for 15 minutes</div>
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
                        <div class="stat-desc">Since there are 4 modules, it will divided with jigsaw session time</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Transition Time</div>
                        <div class="stat-value">2 Minutes</div>
                        <div class="stat-desc">Duration before expert and jigsaw session</div>
                    </div>
                </div>
                <br/>
                <p>Try for yourself!</p>
                <TimeCalculator :number-of-modules="form.topic.no_of_modules"
                                :number-of-students="props.classroomModal.users_count"
                                @out-data="getTime"/>
                <br/>

                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="nextStep"
                        class="btn btn-primary float-right">
                    Proceed
                </button>
            </TitleCard>
            <TitleCard title="Overview" v-if="formStep === 6">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div class="stat w-full border-2">
                        <div class="stat-title">Topic Name</div>
                        <div class="stat-value">{{ form.topic.name }}</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Date and Time</div>
                        <div class="stat-value">{{ displayTime }}</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Number of Modules</div>
                        <div class="stat-value">{{ form.topic.no_of_modules }}</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Grouping Method</div>
                        <div class="stat-value">{{ form.option.groupMethod }}</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Time Method for jigsaw session</div>
                        <div class="stat-value">{{ form.option.timeMethod }}</div>
                    </div>
                </div>
                <div class="divider">Time Session</div>
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div class="stat w-full border-2">
                        <div class="stat-title">Total Time</div>
                        <div class="stat-value">{{form.topic.max_session}} Minutes</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Buffer Time</div>
                        <div class="stat-value">{{form.topic.max_buffer}} Minutes</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Expert Session</div>
                        <div class="stat-value">{{ form.topic.max_time_expert }} Minutes</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Jigsaw Session</div>
                        <div class="stat-value">{{ form.topic.max_time_jigsaw }} Minutes</div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Time for Student Present</div>

                        <!--todo: need to display for uneven-->

                        <div v-if="form.option.timeMethod==='even'"
                             class="stat-value">{{ form.option.tm[0] }} Minutes
                        </div>
                    </div>
                    <div class="stat w-full border-2">
                        <div class="stat-title">Transition Time</div>
                        <div class="stat-value">{{ form.topic.transition_time }} Minutes</div>
                    </div>
                </div>
                <div class="divider">Modules</div>
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div v-for="(moduleData,index) in form.modules" :key="moduleData">
                        <div class="stat w-full border-2">
                            <div class="stat-title">Module {{ index + 1 }}</div>
                            <div class="stat-value">{{ moduleData.name }}</div>
                        </div>
                    </div>
                </div>
                <div v-for="error in errors"
                     class="alert alert-error w-full shadow-lg mt-4">
                    <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
                    <p>{{ error.valueOf() }}</p>
                </div>
                <button @click.prevent="submit"
                        class="btn btn-primary float-right mt-10">
                    Submit Topic
                </button>
            </TitleCard>
        </div>
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


const formStep = ref(1);
const evenTime = ref(0);
const wizardStatus = usePage().props.auth.user.wizard_status


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

const modules = reactive([])

const tm = reactive({
    1: 0,
    2: 0,
    3: 0,
    4: 0,
    5: 0,
    6: 0
})

const option = reactive({
    groupMethod: 'random',
    timeMethod: 'even',
    tm: tm
})

const form = useForm({
    classroom_id: props.classroomModal.id,
    topic: topic,
    modules: modules,
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
    for (let i = 0; i < 6; i++) {
        tm[i] = value.outStudentPresent;
    }
}

const displayTime = ref(null);

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
const submit = () => {
    router.post(route('topic.store'), form)
}

const nextStep = () => {
    if (wizardStatus === 'onCreateTopic') {
        if (formStep.value === 1) {
            router.post(route('topic.wizard.step'), {
                steps: 1,
                name: form.topic.name,
                date_time: form.topic.date_time
            }, {
                onSuccess: () => {
                    formStep.value++
                }
            })
        } else if (formStep.value === 2) {
            router.post(route('topic.wizard.step'), {
                steps: 2,
                no_of_modules: form.topic.no_of_modules,
            }, {
                onSuccess: () => {
                    formStep.value++
                    for (let i = 0; i < form.topic.no_of_modules; i++) {
                        modules.push({
                            name: dummyData.modules[i],
                            learning_objectives: '',
                            file_path: '',
                        })
                    }
                }
            })
        } else if (formStep.value === 3) {
            router.post(route('topic.wizard.step'), {
                steps: 3,
                modules: form.modules,
            }, {
                onSuccess: () => {
                    formStep.value++
                }
            })
        } else if (formStep.value === 4) {
            router.post(route('topic.wizard.step'), {
                steps: 4,
                option: form.option,
            }, {
                onSuccess: () => {
                    formStep.value++
                }
            })
        } else if (formStep.value === 5) {
            router.post(route('topic.wizard.step'), {
                steps: 5,
                max_session: form.topic.max_session,
                max_buffer: form.topic.max_buffer,
            }, {
                onSuccess: () => {
                    formStep.value++
                    displayTime.value = formatDate(new Date(form.topic.date_time))
                }
            })
        } else if (formStep.value === 6) {
            router.post(route('topic.wizard.step'), {
                steps: 6,
                date_time: form.topic.date_time,
            }, {
                onSuccess: () => {
                    formStep.value++
                }
            })
        }
    } else {
        if (formStep.value === 1) {
            router.post(route('topic.first.step'), {
                name: form.topic.name,
                date_time: form.topic.date_time,
                no_of_modules: form.topic.no_of_modules,
                max_time_expert: form.topic.max_time_expert,
                max_time_jigsaw: form.topic.max_time_jigsaw,
                transition_time: form.topic.transition_time,

            }, {
                onSuccess: () => {
                    formStep.value++;
                    for (let i = 0; i < form.topic.no_of_modules; i++) {
                        modules.push({
                            name: '',
                            learning_objectives: '',
                            file_path: ''
                        })
                    }
                }
            });
        } else if (formStep.value === 2) {
            router.post(route('topic.second.step'), {
                modules,
            }, {
                onSuccess: () => {
                    formStep.value++
                    evenTime.value = topic.max_time_jigsaw / parseInt(topic.no_of_modules)
                    evenTimeFunction();
                }
            });
        }
    }
}
</script>
