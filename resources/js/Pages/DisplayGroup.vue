<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">{{ props.topicModal.name }}</h2>
                <p>id = {{ props.topicModal.id }}</p>
                <p>EG = {{ props.expertGroupUserModal.length }}</p>
                <p>JG = {{ props.jigsawGroupUserModal.length }}</p>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div v-for="groupData in props.expertGroupUserModal" class="card w-96 bg-base-100 shadow-xl m-2">
                <div class="card-body">
                    <h2 class="card-title">{{ groupData.name }}</h2>
                    <p>number = {{ groupData.users.length }}</p>
                    <div v-for="userData in groupData.users">
                        <p>{{ userData.name }}</p>
                    </div>
                </div>
            </div>
            <div v-for="groupData in props.jigsawGroupUserModal" class="card w-96 bg-base-100 shadow-xl m-2">
                <div class="card-body">
                    <h2 class="card-title">{{ groupData.name }}</h2>
                    <p>number = {{ groupData.users.length }}</p>
                    <div v-for="userData in groupData.users">
                        <p>{{ userData.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    topicModal: Object,
    expertGroupUserModal: Object,
    jigsawGroupUserModal: Object,

})
</script>

<style scoped>

</style>
