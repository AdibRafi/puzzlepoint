<template>
    <Head title="Add Student"/>
    <Layout>
        <TitleCard title="Add Student">
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
            <br/>
            <ul class="grid w-full gap-6 md:grid-cols-2">
                <li>
                    <input type="radio" id="male" name="gender" value="male"
                           class="hidden peer" v-model="form.gender">
                    <label for="male"
                           class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Male</p>
                  </span>
                        <font-awesome-icon icon="fa-solid fa-person" size="xl"/>
                    </label>
                </li>
                <li>
                    <input type="radio" id="female" name="gender" value="female"
                           class="hidden peer" v-model="form.gender">
                    <label for="female"
                           class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">Female</div>
                        </div>
                        <font-awesome-icon icon="fa-solid fa-person-dress" size="xl"/>
                    </label>
                </li>
            </ul>
            <div class="divider">File Upload</div>
            <div class="mt-4">
                <div v-if="wizardStatus === 'onAddStudent'"
                     class="alert alert-info shadow-lg">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Or you can insert the list of file in xlsx
                        <br/>Format: 1st column = Name, 2nd column = Email, 3rd column = Gender</span>
                    </div>
                </div>
                <p class="mt-4">Format: Column 1 = Name, Column 2 = Email, Column 3 = Gender</p>
                <div class="form-control w-1/2 mb-4">
                    <label class="label">
                        <span class="label-text">Pick a file to upload students</span>
                    </label>
                    <input type="file"
                           class="file-input file-input-bordered file-input-primary w-full"
                           @input="form.file_path = $event.target.files[0]"/>
                </div>
                <div
                    :class="wizardStatus === 'onCreateTopic' ? 'tooltip tooltip-open tooltip-bottom md:tooltip-right tooltip-info' : ''"
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
        <TitleCard :title="'Total Student: ' + props.studentsModal.length">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="studentData in props.studentsModal" :key="studentData">
                        <td>{{ studentData.name }}</td>
                        <td>{{ studentData.email }}</td>
                        <td>{{ studentData.gender }}</td>
                        <td class="btn btn-circle btn-warning opacity-75"
                            @click.prevent="deleteStudent(studentData.id)">
                            <font-awesome-icon icon="fa-solid fa-trash" size="lg"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
import GridLayout from "@/Components/GridLayout.vue";


let wizardStatus = usePage().props.auth.user.wizard_status;

const props = defineProps({
    classroomModal: Object,
    studentsModal: Object,
})

const form = useForm({
    classroom_id: props.classroomModal.id,
    name: '',
    email: '',
    gender: 'male',
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

const deleteStudent = (id) => {
    router.post(route('add-student.remove'), {
        classroom_id: props.classroomModal.id,
        student_id: id
    });
}
</script>
