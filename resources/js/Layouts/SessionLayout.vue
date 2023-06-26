<template>
   <div class="navbar flex justify-between bg-base-100 z-10 shadow-md">
      <h1 class="text-2xl font-semibold ml-10">{{ pageTitle }}</h1>
   </div>
   <main class="flex-1 overflow-y-auto pt-8 px-6 bg-base-200 min-h-screen">
      <slot/>
      <div class="h-16"/>
   </main>
</template>
<script setup>

import {onMounted} from "vue";
import {themeChange} from "theme-change";

defineProps({
   pageTitle: String,
})

onMounted(() => {
   themeChange(false);
})
</script>
