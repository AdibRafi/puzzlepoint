<template>
    <div class="drawer drawer-mobile">
        <input id="left-sidebar-drawer" type="checkbox"
               class="drawer-toggle">
        <PageContent :page-title="pageTitle">
            <slot/>
        </PageContent>
        <LeftSidebar v-if="$page.props.auth.user.is_wizard_complete === 1"/>
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
