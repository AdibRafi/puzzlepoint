<template>
   <div class="drawer drawer-mobile">
      <input id="left-sidebar-drawer" type="checkbox"
             class="drawer-toggle">
      <PageContent :page-title="pageTitle">
         <slot/>
      </PageContent>
      <LeftSidebar/>
   </div>
</template>

<script setup>
import PageContent from "@/Layouts/PageContent.vue";
import {onMounted} from "vue";
import {themeChange} from "theme-change";
import LeftSidebar from "@/Layouts/LeftSidebar.vue";

defineProps({
   pageTitle: String
})

onMounted(() => {
   themeChange(false);
})
</script>
