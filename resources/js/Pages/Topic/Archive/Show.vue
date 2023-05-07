<template>
    <MainLayout>
        <Card title="DEVELOPER">

        </Card>
        <Card :title="props.topic.name">
            <p>Number of Modules = {{ props.topic.no_of_modules }}</p>
        </Card>
        <div class="divider">Expert Group</div>
        <Card v-for="groupData in props.expertGroupModal" :key="groupData"
        :title="groupData.name">
            <p class="text-xl">{{groupData.module.name}}</p>
            <p v-for="userData in groupData.users" :key="userData">
                {{userData.name}}
            </p>
        </Card>
        <div class="divider">Jigsaw Group</div>
        <Card v-for="groupData in props.jigsawGroupModal" :key="groupData"
              :title="groupData.name">
            <p v-for="userData in groupData.users" :key="userData">
                {{userData.name}}
            </p>
        </Card>
    </MainLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topic: Object,
    expertGroupModal: Object,
    jigsawGroupModal: Object,

})
</script>
