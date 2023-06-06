<template>
    <Head title="Display Group"/>
    <Layout page-title="Display Group">
        <button @click.prevent="router.get(route('migrate.group'),{
                students:123
            })" class="btn btn-secondary">GROUP SEEDER
        </button>
        <div class="flex flex-wrap">
            <form @submit.prevent="form.post(route('test.store'))">
                <div class="card w-96 bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Press to generate group</h2>
                        <div class="card-actions justify-end">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">Store</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{ props.topicModal.name }}</h2>
                    <p>id = {{ props.topicModal.id }}</p>
                    <p>EG = {{ props.expertGroupUserModal.length }}</p>
                    <p>JG = {{ props.jigsawGroupUserModal.length }}</p>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="card w-96 bg-base-100 shadow-xl m-2">
                <div class="card-body">
                    <h2 class="card-title">Absent</h2>
                    <div v-for="userData in props.absentStudentModal">
                        <p class="text-red-600">{{ userData.name }}</p>
                    </div>
                </div>
            </div>
            <TitleCard>
                <div class="grid mt-2 md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6">
                    <div v-for="groupData in props.expertGroupUserModal">
                        <CardTable :title="groupData.name"
                                   card-style="">
                            <p>{{ groupData.users.length }}</p>
                            <div class="overflow-x-auto">
                                <table class="table w-full table-compact">
                                    <thead>
                                    <tr>
                                        <th class="w-3/4">Name</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="userData in groupData.users" :key="userData">
                                        <td>{{ userData.name }}</td>
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
                            <p>{{ groupData.users.length }}</p>
                            <div class="overflow-x-auto">
                                <table class="table w-full table-compact">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="userData in groupData.users" :key="userData">
                                        <td>{{ userData.name }}</td>
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

// router.on('start', (e) => {
//     router.reload();
// });

const props = defineProps({
    topicModal: Object,
    expertGroupUserModal: Object,
    jigsawGroupUserModal: Object,
    absentStudentModal: Object,

})

const form = useForm({
    topic_id: 1,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: {},

})
</script>

<style scoped>

</style>
