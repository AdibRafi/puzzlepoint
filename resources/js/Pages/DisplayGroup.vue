<template>
    <Head title="Display Group"/>
    <Layout page-title="Display Group">
        <div class="flex flex-wrap">
            <TitleCard title="List of Generated Group"
                       top-right-button-label="Generate Group"
                       @button-function="form.post(route('test.store'))">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <div class="stat border-2">
                        <InputText label-title="Number of students"
                                   v-model="numStudents"/>
                        <InputText label-title="Number of modules"
                                   v-model="numModules"/>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Include studentTest</span>
                                <input type="checkbox"
                                       class="checkbox"
                                       v-model="is_fixedStudent"/>
                            </label>
                        </div>
                        <div class="divider">Group</div>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Random</span>
                                <input type="radio" name="radio-10" value="random" class="radio"
                                       v-model="groupType"/>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Gender</span>
                                <input type="radio" name="radio-10" value="gender" class="radio"
                                       v-model="groupType"/>
                            </label>
                        </div>
                        <button @click.prevent="router.get(route('migrate.group'),{
                                students:numStudents,
                                modules:numModules,
                                fixed_student:is_fixedStudent,
                                group_type:groupType,
                            })" class="btn btn-primary">GROUP SEEDER
                        </button>
                    </div>
                    <div class="stat border-2">
                        <h2 class="card-title">{{ props.topicModal.name }}</h2>
                        <p>id = {{ props.topicModal.id }}</p>
                        <p>EG = {{ props.expertGroupUserModal.length }}</p>
                        <p>JG = {{ props.jigsawGroupUserModal.length }}</p>
                        <p>total students = {{ props.totalStudents.length }}</p>
                        <p>total modules = {{ props.topicModal.modules.length }}</p>
                    </div>
                </div>
                <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                    <div v-for="groupData in props.expertGroupUserModal">
                        <CardTable :title="groupData.name"
                                   card-style="">
                            <p>Total Student: <span class="font-semibold text-xl">{{ groupData.users.length }}</span>
                            </p>
                            <p>Module: <span class="font-semibold text-xl">{{ groupData.module.name }}</span></p>
                            <div class="overflow-x-auto">
                                <table class="table w-full table-compact">
                                    <thead>
                                    <tr>
                                        <th class="w-3/4">Name</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="userData in groupData.users" :key="userData">
                                        <td>{{ userData.name }}</td>
                                        <td>{{ userData.gender }}</td>
                                        <td v-if="userData.attendances[0].attend_status === 'present'">
                                            <div class="badge badge-success">
                                                Present
                                            </div>
                                        </td>
                                        <td v-else>
                                            <div class="badge badge-error">
                                                Absent
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </CardTable>
                    </div>
                </div>
                <div class="divider"/>
                <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                    <div v-for="groupData in props.jigsawGroupUserModal">
                        <CardTable :title="groupData.name"
                                   card-style="">
                            <p>Total student: <span class="text-xl font-semibold">{{ groupData.users.length }}</span>
                            </p>
                            <div class="overflow-x-auto">
                                <table class="table w-full table-compact">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Module</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="userData in groupData.users" :key="userData">
                                        <td>{{ userData.name }}</td>
                                        <td>{{ userData.gender }}</td>
                                        <td>{{ userData.pivot.module_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </CardTable>
                    </div>
                </div>
            </TitleCard>
        </div>
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import '../bootstrap';
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import CardTable from "@/Components/CardTable.vue";
import Card from "@/Components/Card.vue";
import InputText from "@/Components/InputText.vue";
import {ref} from "vue";
import Checkbox from "@/Components/Archives/Checkbox.vue";

// router.on('start', (e) => {
//     router.reload();
// });

const props = defineProps({
    topicModal: Object,
    expertGroupUserModal: Object,
    jigsawGroupUserModal: Object,
    absentStudentModal: Object,
    totalStudents: Object,

})

const form = useForm({
    topic_id: 1,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: {},

})

const numStudents = ref();
const numModules = ref();
const is_fixedStudent = ref(false);
const groupType = ref('random');
</script>

<style scoped>

</style>
