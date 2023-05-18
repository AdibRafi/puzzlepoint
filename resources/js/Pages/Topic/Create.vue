<template>
   <Layout page-title="Create Topic">
      <Card title="DEVELOPER">
         <!--         {{ errors }}-->
         <!--         {{ form.classroom_id }}-->
         {{ formStep }}
         <p>modules = {{ modules.length }}</p>
         <p>even time = {{ evenTime }}</p>
         <p>tm = {{option.tm}}</p>
      </Card>
      <ul class="steps w-full">
         <li class="step step-primary">Topic</li>
         <li :class="'step ' + (formStep === 2 || formStep === 3 ? 'step-primary':'')">Modules</li>
         <li :class="'step ' + (formStep === 3 ? 'step-primary':'')">Options</li>
      </ul>
      <TitleCard title="Add Topic" v-if="formStep===1">
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <InputText label-title="Topic Name" input-type="text"
                       v-model="topic.name"/>
            <InputText label-title="Number of Modules" input-type="number"
                       v-model="topic.no_of_modules"/>
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
            <InputText label-title="Transition Time" input-type="number"
                       v-model="topic.transition_time"/>
            <InputText label-title="Date" input-type="datetime-local"
                       v-model="topic.date_time"/>
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
            <button @click.prevent="nextStep"
                    class="btn btn-primary float-right">
               Proceed
            </button>
         </div>
      </TitleCard>
      <TitleCard title="Add Modules" v-if="formStep===2">
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
               <input type="radio" id="genderFixed" name="groupOption" value="genderFixed" v-model="option.groupMethod"
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
                     <p class="w-full">Each Student will present for {{evenTime}} minutes</p>
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
               <InputText :label-title="'Time for Module ' + (index+1)" input-type="number" v-model="option.tm[index+1]"/>
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
                    class="btn btn-primary float-right">
               Submit
            </button>
         </div>
      </TitleCard>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {onUpdated, reactive, ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import InputForm from "@/Components/InputForm.vue";
import Layout from "@/Layouts/Layout.vue";
import {router} from "@inertiajs/vue3";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const formStep = ref(1);
const evenTime = ref(0);

const props = defineProps({
   classroom_id: Object,
   errors: Object,
})

const topic = reactive({
   name: '',
   date_time: '',
   no_of_modules: '',
   max_time_expert: '',
   max_time_jigsaw: '',
   transition_time: '',
})

const modules = reactive([])

const tm = reactive({
   1 : 0,
   2 : 0,
   3 : 0,
   4 : 0,
   5 : 0,
   6 : 0
})

const option = reactive({
   groupMethod: 'random',
   timeMethod: 'even',
   tm: tm
})

const form = useForm({
   classroom_id: props.classroom_id,
   topic: topic,
   modules: modules,
   option: option,
})


const unevenTimeFunction = () => {
   tm[1] = 0
   tm[2] = 0
   tm[3] = 0
   tm[4] = 0
   tm[5] = 0
   tm[6] = 0
}

const evenTimeFunction = () => {
   tm[1] = evenTime.value
   tm[2] = evenTime.value
   tm[3] = evenTime.value
   tm[4] = evenTime.value
   tm[5] = evenTime.value
   tm[6] = evenTime.value
}
const submit = () => {
   form.post(route('topic.store'))
}

const nextStep = () => {
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
      })
   } else if (formStep.value === 2) {
      router.post(route('topic.second.step'), {
         modules,
      }, {
         onSuccess: () => {
            formStep.value++
            evenTime.value = topic.max_time_jigsaw / parseInt(topic.no_of_modules)
            evenTimeFunction();
         }
      })
   }
}
</script>
