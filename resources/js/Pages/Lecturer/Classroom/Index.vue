<template>
    <Head title="Dashboard" />
    <MainLayout>
        <div class="w-screen">
            <div class="flex justify-end my-2 mx-6">
                <Link :href="route('classroom.create')">
                    <button class="btn btn-primary">Add Class</button>
                </Link>
            </div>
            <div v-for="data in classroomData" key="data" class="flex justify-center">
                <div class="card w-3/4 bg-base-300 shadow-xl m-4">
                    <div class="card-body">
                        <h2 class="card-title">{{ data.name }}</h2>
                        <p>{{ data.subject_code }}</p>
                        <div class="card-actions justify-end">
                            <button class="btn btn-primary">Add Topic</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-11/12 bg-base-300 shadow-xl m-4">
                <div class="card-body">
                    <h2 class="card-title">Demo Class</h2>
                    <p>Demo Subject Code</p>
                    <div class="card w-5/12 bg-base-100 shadow-xl m-4">
                        <div class="card-body">
                            <p>Date = 1/5/2023</p>
                            <p>Topic = Demo Topic</p>
                            <p>Module = mt1, mt2, mt3, mt4</p>
                            <p>Expert Time = 30 min</p>
                            <p>Jigsaw Time = 60 min</p>
                            <div class="card-actions justify-end">
                                <button class="btn btn-primary">Start</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">Add Topic</button>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>

</template>


<script setup>
import {Head, Link} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    classroomData: Object,
})

const datas2 = [
    {
        name: 'Class1',
        code: 'TGD1202'
    },
    {
        name: 'Class2',
        code: 'TCP1302'
    },
    {
        name: 'Class3',
        code: 'TMU2231'
    },
    {
        name: 'Class4',
        code: 'TSP3301'
    }

]

</script>

<style scoped>

</style>
