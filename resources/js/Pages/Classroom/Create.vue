<template>
   <Head title="Create Classroom"/>
   <Layout>
      <form @submit.prevent="form.post(route('classroom.store'));">
         <TitleCard title="Create Classroom">
            <InputText label-title="Classroom Name" v-model="form.name"/>
            <InputText label-title="Subject Code" v-model="form.subject_code"/>
            <div class="mt-6">
               <button @click="window.history.back()"
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
import {Head, Link, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const form = useForm({
   name: '',
   subject_code: '',
})
</script>

<style scoped>

</style>
