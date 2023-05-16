<template>
   <Head title="Edit Classroom"/>
   <Layout>
      <form @submit.prevent="form.put(route('classroom.update',form.id));">
         <TitleCard title="Edit Classroom" top-right-button-label="Destroy"
                    @button-function="destroy(props.classroom.id)">
            <InputText label-title="Classroom Name" v-model="form.name"/>
            <InputText label-title="Subject_code" v-model="form.subject_code"/>
            <div class="divider"/>
            <button @click="back" class="btn btn-accent mx-2">Cancel</button>
            <button type="submit" :disabled="form.processing"
                    class="btn btn-primary float-right mx-2">Update Classroom
            </button>
         </TitleCard>
      </form>
   </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import Card from "@/Components/Card.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const props = defineProps({
   classroom: Object,
})

const form = useForm(props.classroom)

const back = () => {
   window.history.back();
}

const destroy = (id) => {
   if (confirm('Are you sure to delete?')) {
      router.delete(route('classroom.destroy', id));
   }else {
      router.reload()
   }
}
</script>

<style scoped>

</style>
