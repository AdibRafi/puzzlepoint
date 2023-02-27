<template>
    <MainLayout>
        <div v-for="answer in answers">
            <p>{{ answer.rightAns }}</p>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    answers: Object,
})
</script>

<style scoped>

</style>
