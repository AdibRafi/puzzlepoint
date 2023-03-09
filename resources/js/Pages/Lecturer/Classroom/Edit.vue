<template>
    <Head title="Create"/>
    <MainLayout>
        <form @submit.prevent="form.put(route('classroom.update',form.id));">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Create Classroom</h2>
                    <div class="card-actions justify-end">
                        <InputForm label-name="Classroom Name" v-model="form.name"/>
                        <InputForm label-name="Subject Code" v-model="form.subject_code"/>
                        <div class="my-4">
                            <Link :href="route('classroom.index')" class="btn btn-accent mx-2">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">Update Classroom</button>
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

const props = defineProps({
    classroom : Object,
})

const form = useForm(props.classroom)
</script>

<style scoped>

</style>
