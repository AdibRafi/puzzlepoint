<template>
    <MainLayout>
<!--        <Card title="DEVELOPER">-->
<!--            <div v-for="items in props.jigsawGroupUserModal">-->
<!--                <p>{{ items.name }}</p>-->
<!--                <div v-for="items2 in items.users">-->
<!--                    {{items2.name}}-->
<!--                </div>-->
<!--            </div>-->
<!--        </Card>-->
        <Card title="Expert Session" class="my-4">
            <p>{{props.topicModuleModal.name}}</p>
        </Card>
        <Card title="Timer" class="my-4">

        </Card>
        <div v-for="groupData in props.expertGroupUserModal">
            <Card :title="groupData.name" class="my-4">
                <div v-for="userData in groupData.users">
                    <p>{{userData.name}}</p>
                </div>
            </Card>
        </div>

    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
    expertGroupUserModal: Object,

})
</script>

<style scoped>

</style>
