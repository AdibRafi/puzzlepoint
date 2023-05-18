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
      <TitleCard title="thing">
         <div class="overflow-x-auto">
            <table class="table w-full">
               <!-- head -->
               <thead>
               <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Message</th>
               </tr>
               </thead>
               <tbody>
               <!-- row 1 -->
               <tr v-for="(message,index) in messages" :key="message">
                  <th>{{ index + 1 }}</th>
                  <td>{{message.name}}</td>
                  <td>{{message.messageInput}}</td>
               </tr>
               </tbody>
            </table>
         </div>
         <InputText input-type="text" label-title="Message" v-model="inputMessage"/>
         <button @click="addMessage" class="btn btn-primary w-full mt-6">Add</button>
      </TitleCard>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import {onMounted, reactive, ref} from "vue";
import Card from "@/Components/Card.vue";
import VuePdfEmbed from "vue-pdf-embed";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {useStore} from 'vuex'
import InputText from "@/Components/InputText.vue";

const t = () => {
   console.log('tah')
}

console.log(usePage().props.auth.classroom)

const messages = reactive([])
const inputMessage = ref(null);
const addMessage = () => {
   messages.push({
      name: 'adib',
      messageInput: inputMessage.value
   })
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


onMounted(() => {
   console.log('mounted')
});

</script>

<style scoped>

</style>
