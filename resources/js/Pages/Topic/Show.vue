<template>
   <Head title="Topic"/>
   <Layout :page-title="props.topic.name">
      <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
         <div class="state shadow bg-base-100">
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
         <div class="state shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-secondary">
                  <font-awesome-icon icon="fa-solid fa-user" size="xl"/>
               </div>
               <div class="stat-title">Total Students</div>
               <div class="stat-value">{{ props.studentModal.length }}</div>
               <div class="stat-desc">.</div>
            </div>
         </div>
         <div class="state shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-primary">
                  <font-awesome-icon icon="fa-solid fa-hourglass" size="xl"/>
               </div>
               <div class="stat-title">Expert Time</div>
               <div class="stat-value">{{ props.topic.max_time_expert }}</div>
               <div class="stat-desc">For Expert Session</div>
            </div>
         </div>
         <div class="state shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure text-secondary">
                  <font-awesome-icon icon="fa-solid fa-hourglass" size="xl"/>
               </div>
               <div class="stat-title">Jigsaw Time</div>
               <div class="stat-value">{{ props.topic.max_time_jigsaw }}</div>
               <div class="stat-desc">For Jigsaw Session</div>
            </div>
         </div>
      </div>
      <TitleCard title="Assessment">
         <div v-if="props.assessmentModal">
            <p>test</p>
         </div>
         <div v-else>
            <h2 class="card-title">You don't have assessment yet</h2>
            <button @click="router.get(route('assessment.index',{topic_id:props.topic.id}))"
                    class="btn btn-primary">Create
            </button>
         </div>
      </TitleCard>
      <div class="flex items-center">
         <Card>
            <button @click="openSession"
                    class="btn btn-accent">start
            </button>
         </Card>
      </div>
   </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Head, Link, router} from "@inertiajs/vue3";
import {ref} from "vue";
import TitleCard from "@/Components/TitleCard.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
   topic: Object,
   moduleModal: Object,
   studentModal: Object,
   assessmentModal: Object,
})

const isModuleExpand = ref(false);

const setModuleExpand = () => {
   isModuleExpand.value = !isModuleExpand.value
   console.log(isModuleExpand.value)
}

const openSession = () => {
   window.open(route('lecturer.session.index', {topic_id: props.topic.id}), '_blank')
}
</script>
