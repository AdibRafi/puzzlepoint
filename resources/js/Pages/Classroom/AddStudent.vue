<template>
    <Head title="Add Student"/>
    <Layout>
        <TitleCard title="Add Student">
            <div v-if="wizardStatus === 'onAddStudent'"
                 class="alert alert-info shadow-lg">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span class="ml-4">Fill in the name and email (optional)</span>
                </div>
            </div>
            <InputText label-title="Student Name"
                       v-model="form.name"
                       :is-disabled="wizardStatus==='onAddStudent'"
            />
            <InputText label-title="Email (optional)"
                       input-type="email"
                       v-model="form.email"
                       :is-disabled="wizardStatus==='onAddStudent'"/>
            <div class="mt-6">
                <h2 class="card-title">Tutorial</h2>
                <p>Since you are doing a tutorial,
                    you may able to enter a number of dummy <br/>student to generate.</p>
                <p>We recommend on having 20 students.</p>
                <br/>
                <InputText label-title="Number to generate Dummy Students"
                           container-style="my-2"
                           input-type="number"
                           v-model="form.numGenerateStudents"/>
                <button v-if="wizardStatus !== 'onAddStudent'"
                        @click="window.history.back()"
                        class="btn btn-accent">Cancel
                </button>
                <button type="submit" @click.prevent="submit"
                        class="btn btn-primary float-right">Add Student
                </button>
            </div>
        </TitleCard>
    </Layout>
</template>
<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import InputText from "@/Components/InputText.vue";
import TitleCard from "@/Components/TitleCard.vue";

const props = defineProps({
    classroom_id: Object,
})

const form = useForm({
    classroom_id: props.classroom_id,
    numGenerateStudents: null,
    name: '',
    email: '',
})

const submit = () => {
    form.post(route('classroom.add-student.store'));
}

const wizardStatus = usePage().props.auth.user.wizard_status;
</script>
