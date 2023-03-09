<template>
    <Head title="Create"/>
    <MainLayout>
        <form @submit.prevent="form.post(route('classroom.store'));">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Create Classroom</h2>
                    <p>{{form.name}}</p>
                    <p>{{form.subject_code}}</p>
                    <div class="card-actions justify-end">
                        <InputForm label-name="Classroom Name" v-model="form.name"/>
                        <InputForm label-name="Subject Code" v-model="form.subject_code"/>
                        <div class="my-4">
                            <Link :href="route('classroom.index')" class="btn btn-accent mx-2">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">Save Classroom</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import InputForm from "@/Components/inputForm.vue";

const form = useForm({
    name: '',
    subject_code: '',
})
</script>

<style scoped>

</style>
