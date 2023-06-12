<template>
    <div class="drawer lg:drawer-open">
        <input id="left-sidebar-drawer" type="checkbox"
               class="drawer-toggle">
        <PageContent :page-title="pageTitle">
            <slot/>
        </PageContent>
        <LeftSidebar />
    </div>
</template>

<script setup>
import PageContent from "@/Layouts/PageContent.vue";
import {onMounted} from "vue";
import {themeChange} from "theme-change";
import LeftSidebar from "@/Layouts/LeftSidebar.vue";
import {usePage} from "@inertiajs/vue3";

defineProps({
    pageTitle: String
})

onMounted(() => {
    themeChange(false);
})

console.log(usePage().props.auth.user.is_wizard_complete)
</script>
