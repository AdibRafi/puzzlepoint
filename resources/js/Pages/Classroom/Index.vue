<template>
   <Head title="Dashboard"/>
   <Layout title="Classroom">
      <TitleCard title="Classroom" top-right-button-label="Add Class"
                 v-if="$page.props.user.type === 'lecturer'"
                 @button-function="router.get(route('classroom.create'))">
         <div v-for="data in classroomData" :key="data">
            <Card :title="data.name" @click="goClassroom(data.id)"
                  class="cursor-pointer hover:bg-base-200 w-1/2">
               <p>{{ data.subject_code }}</p>
            </Card>
         </div>
      </TitleCard>
      <TitleCard title="Classroom"
                 v-else>
         <div v-for="data in classroomData" :key="data">
            <Card :title="data.name" @click="goClassroom(data.id)"
                  class="cursor-pointer hover:bg-base-200 w-1/2">
               <p>{{ data.subject_code }}</p>
            </Card>
         </div>
      </TitleCard>
   </Layout>

</template>


<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";

const props = defineProps({
   classroomData: Object,
})

const goClassroom = (id) => {
   router.get(route('classroom.show', id))
}

const destroy = (id) => {
   if (confirm('Are you sure to delete?')) {
      router.delete(route('classroom.destroy', id))
   }
}

const datas2 = [
   {
      name: 'Class1',
      code: 'TGD1202'
   },
   {
      name: 'Class2',
      code: 'TCP1302'
   },
   {
      name: 'Class3',
      code: 'TMU2231'
   },
   {
      name: 'Class4',
      code: 'TSP3301'
   }

]

</script>

<style scoped>

</style>
