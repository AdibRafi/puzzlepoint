<template>
<MainLayout>
    <Card title="DEVELOPER">
        <p>test</p>
    </Card>
</MainLayout>
</template>

<script setup>

import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import '../../../bootstrap'

const props = defineProps({
    topicModuleModal: Object,
})

</script>

<style scoped>

</style>
