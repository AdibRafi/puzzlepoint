<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <div v-for="groupData in props.expertGroupUserModal">
                <p>{{groupData.name}}</p>
                <div v-for="userData in groupData.users">
                    <p>{{userData.name}}</p>
                </div>
            </div>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    expertGroupUserModal: Object,

})
</script>

<style scoped>

</style>
