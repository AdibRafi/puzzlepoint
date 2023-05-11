<template>
   <Head title="Test"/>
   <Layout page-title="Test Page">
      <Card title="DEVELOPER"/>
      <TitleCard top-margin="mt-6" title="testlol"
                 top-right-button-label="idk"
                 @click="t"/>
      <Card v-for="classroomData in usePage().props.auth.classrooms" :key="classroomData">
         <p>{{ classroomData.name }}</p>
      </Card>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import {onMounted} from "vue";
import Card from "@/Components/Card.vue";
import VuePdfEmbed from "vue-pdf-embed";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {useStore} from 'vuex'

const t = () => {
   console.log('tah')
}

console.log(usePage().props.auth.classroom)

const topSideButtonFunc = () => {
   const action = useStore();

   const openAddNewLeadModal = () => {
      action.dispatch(openModal({title: "Add New Lead", bodyType: MODAL_BODY_TYPES.LEAD_ADD_NEW}))
   }

   return '<div class="inline-block float-right">\n' +
      '         <button class="btn px-6 btn-sm normal-case btn-primary"  @click="openAddNewLeadModal">Add New\n' +
      '         </button>\n' +
      '      </div>';
   // return (???
   //    <div class="inline-block float-right">
   //       <button class="btn px-6 btn-sm normal-case btn-primary"  onClick={() => openAddNewLeadModal()}>Add New
   //       </button>
   //    </div>
   // )
}


const tmDummy = {
   '1': '15',
   '2': '15',
   '3': '15',
   '4': '15',
}

const props = defineProps({
   data: Object,
})

const form = useForm({
   topic_id: 1,
   groupMethod: 'none',
   timeMethod: 'even',
   tm: tmDummy,
});

const form2 = useForm({
   topic_id: 1,

})

onMounted(() => {
   console.log('mounted')
});

</script>

<style scoped>

</style>
