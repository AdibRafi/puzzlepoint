<template>
<!--    <button @click="buttonMethod" type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800-->
<!--                   border border-transparent rounded-md font-semibold text-xs text-white uppercase-->
<!--                   tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none-->
<!--                   focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25-->
<!--                   transition ease-in-out duration-150 ml-3">{{ buttonName }}</button>-->

    <button class="btn btn-primary"
    @click="buttonMethod">{{ buttonName }}</button>
</template>

<script setup>
import {defineProps} from 'vue';

const props = defineProps({
    buttonName: String,
    buttonMethod: String
})
</script>

<style scoped>

</style>
