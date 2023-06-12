<template>
    <div class="drawer-content flex flex-col">
        <Header :page-title="pageTitle"/>
        <main class="flex-1 overflow-y-auto pt-8 px-6 bg-base-200 min-h-screen">
            <div v-if="$page.props.message.alertMessage"
                 class="alert alert-success w-full shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-check" bounce/>
                    <span class="mx-2">{{ $page.props.message.alertMessage }}</span>
                </div>
            </div>
            <div v-if="$page.props.message.warningMessage"
                 class="alert alert-warning w-full shadow-lg mb-4">
                <div>
                    <font-awesome-icon icon="fa-solid fa-circle-exclamation"/>
                    <span class="mx-2">{{ $page.props.message.warningMessage }}</span>
                </div>
            </div>
            <slot/>
            <div class="mt-16"/>
        </main>
    </div>
</template>

<script setup>
import Header from "@/Layouts/Header.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps({
    pageTitle: String,
})
</script>
