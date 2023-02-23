<template>
<p>test</p>
    <div v-for="answer in answers">
        <p>{{answer.rightAns}}</p>
    </div>
</template>

<script setup>
defineProps({
    answers: Object,
})
</script>

<style scoped>

</style>
