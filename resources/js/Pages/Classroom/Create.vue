<template>
    <Head title="Create Classroom"/>
    <MainLayout>
        <form @submit.prevent="form.post(route('classroom.store'));">
            <Card title="Create Classroom">
                <InputForm label-name="Classroom Name" v-model="form.name"/>
                <InputForm label-name="Subject Code" v-model="form.subject_code"/>
                <template #actions>
                    <div class="mt-2">
                        <Link :href="route('classroom.index')" class="btn btn-accent mx-2">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Update Classroom</button>
                    </div>
                </template>
            </Card>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/InputForm.vue";
import Card from "@/Components/Card.vue";

const form = useForm({
    name: '',
    subject_code: '',
})
</script>

<style scoped>

</style>
