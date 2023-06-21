<template>
    <Head title="Add Student"/>
    <Layout>
        <TitleCard title="Add Student">
            <div class="shadow bg-base-100 border-2 w-1/2 my-2">
                <div class="stat">
                    <div class="stat-title">Total Student</div>
                    <div class="stat-value">{{ props.classroomModal.users_count }}</div>
                    <div class="stat-desc">In classroom = {{ props.classroomModal.name }}</div>
                </div>
            </div>
            <div v-if="wizardStatus === 'onAddStudent'"
                 class="alert alert-info shadow-lg">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                    <span class="ml-4">Here is where you can insert the name of the student
                        <br/>
                        <br/>
                        For this tutorial you need 20 students
                    </span>
                </div>
            </div>
            <div class="divider">Manual</div>
            <InputText label-title="Student Name"
                       v-model="form.name"
            />
            <InputText label-title="Email"
                       input-type="email"
                       v-model="form.email"/>
            <div class="divider">File Upload</div>
            <div class="mt-4">
                <div v-if="wizardStatus === 'onAddStudent'"
                     class="alert alert-info shadow-lg">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Or you can insert the list of file in CSV
                        <br/>Format: 1st column = Name, 2nd column = Email</span>
                    </div>
                </div>
                <p class="mt-4">Format: Column 1 = Name, Column 2 = Email</p>
                <div class="form-control w-1/2 mb-4">
                    <label class="label">
                        <span class="label-text">Pick a file to upload students</span>
                    </label>
                    <input type="file"
                           class="file-input file-input-bordered file-input-primary w-full"
                           @input="form.file_path = $event.target.files[0]"/>
                </div>
                <div :class="wizardStatus === 'onCreateTopic' ? 'tooltip tooltip-open tooltip-bottom md:tooltip-right tooltip-info' : ''"
                     data-tip="Great! You have 20 students, Go back to Classroom Page to continue">
                    <button @click.prevent="router.get(route('classroom.show',props.classroomModal.id))"
                            class="btn btn-accent">Return to Classroom
                    </button>
                </div>
                <button type="submit" @click.prevent="submit"
                        class="btn btn-primary float-right">Add Student
                </button>
            </div>
        </TitleCard>
    </Layout>
</template>
<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import InputText from "@/Components/InputText.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onMounted} from "vue";


let wizardStatus = usePage().props.auth.user.wizard_status;

const props = defineProps({
    classroomModal: Object,
})

const form = useForm({
    classroom_id: props.classroomModal.id,
    name: '',
    email: '',
    file_path: '',

})
onMounted(() => {
    form.reset();
})
const submit = () => {
    form.post(route('classroom.add-student.store'), {
        onSuccess: () => {
            form.reset('name')
            form.reset('email')
            wizardStatus = usePage().props.auth.user.wizard_status
        }
    });

}
</script>
