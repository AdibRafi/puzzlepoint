<template>
   <Head title="Topic"/>
   <Layout :page-title="props.topic.name">
      <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure btn btn-circle btn-primary" @click="setModuleExpand">
                  <font-awesome-icon icon="fa-solid fa-angle-down"
                                     size="xl"
                                     :class="'delay-400 duration-500 transition-all ' + (isModuleExpand ? 'rotate-180' : '')"/>
               </div>
               <div class="stat-title">No of Modules</div>
               <div class="stat-value">{{ props.topic.no_of_modules }}</div>
               <div class="stat-desc">To see the list, Click Button</div>
               <p v-if="isModuleExpand"
                  v-for="moduleData in props.moduleModal" :key="moduleData"
                  class="stat-desc">{{ moduleData.name }}</p>
            </div>
         </div>
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-primary">
                  <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
               </div>
               <div class="stat-title">Total Students</div>
               <div class="stat-value">{{ props.studentModal.length }}</div>
               <div v-if="wizardStatus === 'onStartSession'"
                    class="stat-desc text-info">
                  For this tutorial, we will be using dummy student
               </div>
               <div v-else
                    class="stat-desc">
                  .
               </div>
            </div>
         </div>
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-secondary">
                  <font-awesome-icon icon="fa-solid fa-hourglass-start" size="xl"/>
               </div>
               <div class="stat-title">Expert Time</div>
               <div class="stat-value">{{ props.topic.max_time_expert }}</div>
               <div class="stat-desc">For Expert Session</div>
            </div>
         </div>
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-secondary">
                  <font-awesome-icon icon="fa-solid fa-hourglass-end" size="xl"/>
               </div>
               <div class="stat-title">Jigsaw Time</div>
               <div class="stat-value">{{ props.topic.max_time_jigsaw }}</div>
               <div class="stat-desc">For Jigsaw Session</div>
            </div>
         </div>
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div
                  :class="'stat-figure btn btn-accent btn-circle ' + (wizardStatus==='onStartSession' ? 'btn-disabled':'')"
                  @click.prevent="">
                  <font-awesome-icon icon="fa-solid fa-pen-to-square" size="xl"/>
               </div>
               <div class="stat-title">Assessment</div>
               <div class="stat-value">Not Started</div>
               <div class="stat-desc">To go to assessment page, click button</div>
            </div>
         </div>
         <div class="stats shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure btn btn-accent btn-circle"
                    @click.prevent="openSession">
                  <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" size="xl"/>
               </div>
               <div class="stat-title">Start Session</div>
               <div class="stat-value text-2xl">{{ dateTime }}</div>
               <div class="stat-desc">Click button and it will bring you <br/>to the next page</div>
            </div>
         </div>
      </div>
      <div v-if="wizardStatus === 'onStartSession'"
           class="alert alert-info shadow-lg mt-10">
         <div>
            <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
            <span>
                  This will be the topic menu, Click the start session button to continue
               </span>
         </div>
      </div>
   </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import TitleCard from "@/Components/TitleCard.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
   topic: Object,
   moduleModal: Object,
   studentModal: Object,
   assessmentModal: Object,
})

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

// console.log(formatDate(props.topic.date_time))
const dateTime = formatDate(new Date(props.topic.date_time))
console.log(dateTime)
const isModuleExpand = ref(false);
const wizardStatus = usePage().props.auth.user.wizard_status

const setModuleExpand = () => {
   isModuleExpand.value = !isModuleExpand.value
   console.log(isModuleExpand.value)
}

const openSession = () => {
   window.open(route('lecturer.session.index', {topic_id: props.topic.id}), '_blank')
}
</script>
