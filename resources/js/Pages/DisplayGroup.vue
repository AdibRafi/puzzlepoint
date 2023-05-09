<template>
    <Head title="Display Group"/>
    <MainLayout>
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
                        <p class="text-red-600">{{userData.name}}</p>
                    </div>
                </div>
            </div>
            <div v-for="groupData in props.expertGroupUserModal" class="card w-96 bg-base-100 shadow-xl m-2">
                <div class="card-body">
                    <h2 class="card-title">{{ groupData.name }}</h2>
                    <p>number = {{ groupData.users.length }}</p>
                    <div v-for="userData in groupData.users">
                        <p v-if="userData.attendances[0].attend_status === 'absent'" class="text-red-600">{{ userData.name }} - {{ userData.attendances[0].attend_status }}</p>
                        <p v-else>{{ userData.name }} - {{ userData.attendances[0].attend_status }}</p>
                    </div>
                </div>
            </div>
            <div v-for="groupData in props.jigsawGroupUserModal" class="card w-96 bg-base-100 shadow-xl m-2">
                <div class="card-body">
                    <h2 class="card-title">{{ groupData.name }}</h2>
                    <p>number = {{ groupData.users.length }}</p>
                    <div v-for="userData in groupData.users">
                        <p v-if="userData.attendances[0].attend_status === 'absent'" class="text-red-600">{{ userData.name }} - {{ userData.attendances[0].attend_status }}</p>
                        <p v-else>{{ userData.name }} - {{ userData.attendances[0].attend_status }}</p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import '../bootstrap';

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
