<template>
    <Head title="Test"/>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Card title!</h2>
                <p>If a dog chews shoes whose shoes does he choose?</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-primary">Buy Now</button>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-6">
            <div class="card-body">
                <h2 class="card-title">{{ data }}</h2>
            </div>
        </div>
        <div v-for="data in data">
            <div class="card w-96 bg-base-100 shadow-xl my-6">
                <div class="card-body">
                    <h2 class="card-title">{{ data.name }}</h2>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
    data: Object,
})
</script>

<style scoped>

</style>
