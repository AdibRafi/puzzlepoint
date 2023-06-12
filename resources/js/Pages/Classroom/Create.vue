<template>
   <Head title="Create Classroom"/>
   <Layout>
<!--       todo: place the error alert-->
      <form @submit.prevent="form.post(route('classroom.store'));">
         <TitleCard title="Create Classroom">
            <div v-if="wizardStatus === 'onCreateClassroom'"
               class="alert alert-info shadow-lg">
               <div>
                  <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                  <span class="ml-4">Fill in Classroom Name and Subject Code</span>
               </div>
            </div>
            <InputText label-title="Classroom Name" v-model="form.name"/>
            <InputText label-title="Subject Code" v-model="form.subject_code"/>
            <div class="mt-6">
               <button v-if="wizardStatus !== 'onCreateClassroom'"
                  @click="window.history.back()"
                       class="btn btn-accent">Cancel
               </button>
               <button type="submit" :disabled="form.processing"
                       class="btn btn-primary float-right">Create Classroom
               </button>
            </div>
         </TitleCard>
      </form>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const form = useForm({
   name: '',
   subject_code: '',
})

const wizardStatus = usePage().props.auth.user.wizard_status;
</script>

<style scoped>

</style>
