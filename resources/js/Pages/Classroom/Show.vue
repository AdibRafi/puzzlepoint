<template>
   <Head :title="props.classroom.name"/>
   <Layout :page-title="props.classroom.name">
      <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
         <div class="state shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure btn btn-circle btn-primary">
                  <Link :href="route('topic.create',{classroom_id : props.classroom.id})">
                     <font-awesome-icon icon="fa-solid fa-plus" size="xl"/>
                  </Link>
               </div>
               <div class="stat-title">Total Topic</div>
               <div class="stat-value">{{ topicModal.length }}</div>
               <div class="stat-desc">
                  To add more, Click the + Button
               </div>
            </div>
         </div>
         <div class="state shadow bg-base-100">
            <div class="stat">
               <div class="stat-figure btn btn-circle btn-secondary">
                  <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" size="xl"/>
               </div>
               <div class="stat-title">Total Archive</div>
               <div class="stat-value">SOMETHING</div>
               <div class="stat-desc">Click the button to see Archive</div>
            </div>
         </div>
      </div>
      <TitleCard :title="props.classroom.name" top-right-button-label="Edit Class"
                 @button-function="router.get(route('classroom.edit',props.classroom.id))">

         <h2 class="card-title">List of Created Topics</h2>
         <div v-for="data in topicModal" :key="data">
            <Card :title="data.name" @click="goTopic(data.id)"
                  class="cursor-pointer hover:bg-base-200 w-1/2">
               <p>Modules = {{data.no_of_modules}}</p>
            </Card>
         </div>
      </TitleCard>
      <Card :title="props.classroom.name">
         <p>{{ props.classroom.subject_code }}</p>
         <template #actions v-if="$page.props.user.type === 'lecturer'">
            <button @click="destroyClassroom(props.classroom.id)" type="button" class="btn btn-warning">Delete Class
            </button>
            <Link :href="route('classroom.edit',props.classroom.id)" class="btn btn-accent">Edit Class</Link>
         </template>
      </Card>
      <div v-if="$page.props.user.type === 'lecturer'">
         <Card :title="'You have '+props.topicModal.length + ' topic'"
               class="flex">
            <Link :href="route('topic.create',{classroom_id:props.classroom.id})"
                  class="btn btn-primary flex-grow">
               Add Topic
            </Link>
            <div class="divider"/>
            <h2 class="card-title">You have archive</h2>
            <Link :href="route('topic.archive.index',{classroom_id:props.classroom.id})"
                  class="btn btn-secondary">
               Go To Archive
            </Link>
         </Card>
      </div>
      <!--        TOPIC-->
      <div v-for="data in props.topicModal" :key="data" class="my-4">
         <Card :title="data.name">
            <p>{{ data.date_time }}</p>
            <!--todo: change to actual modules names-->
            <p>No. of Modules = {{ data.no_of_modules }}</p>
            <p>expert time = {{ data.max_time_expert }}</p>
            <p>jigsaw time = {{ data.max_time_jigsaw }}</p>
            <!--todo: do dropdown for module option etc-->
            <template #actions>
               <div v-if="$page.props.user.type ==='lecturer'">
                  <div class="dropdown dropdown-hover">
                     <label tabindex="0" class="btn m-1 btn-accent">Edit</label>
                     <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                        <li>
                           <Link :href="route('module.editIndex',{topic_id:data.id})">Module</Link>
                        </li>
                        <li>
                           <Link :href="route('option.editIndex',{
                                topic_id:data.id,
                            })">Option
                           </Link>
                        </li>
                     </ul>
                  </div>
                  <div v-if="data.is_ready === 1">
                     <Link :href="route('lecturer.session.index',{topic_id: data.id})" class="btn btn-primary">
                        Start
                     </Link>
                  </div>
                  <button @click="destroyTopic(data.id)" type="button" class="btn btn-warning">Delete
                  </button>
                  <Link :href="route('assessment.index',{topic_id:data.id})" class="btn btn-primary">
                     Assessment
                  </Link>

               </div>
               <div v-if="$page.props.user.type === 'student'">
                  <div v-if="data.is_start === 1">
                     <Link :href="route('student.session.index',{topic_id: data.id})" class="btn btn-secondary">
                        student
                        start session
                     </Link>
                  </div>
                  <Link :href="route('student.assessment.index',{topic_id:data.id})"
                        class="btn btn-accent">
                     Student Assessment
                  </Link>
               </div>
            </template>
         </Card>
      </div>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
   classroom: Object,
   topicModal: Object,
})

const goTopic = (id) => {
   router.get(route('topic.show', id))
}

// console.log(props.topicModal.length);
const destroyClassroom = (id) => {
   if (confirm('Are you sure to delete?')) {
      router.delete(route('classroom.destroy', id))
   }
}

const destroyTopic = (id) => {
   if (confirm('Are you sure to delete?')) {
      router.delete(route('topic.destroy', id));
   }
}

</script>

