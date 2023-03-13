<template>
    <!--    todo: Head - change to class name-->
    <Head title="Show"/>
    <MainLayout>
        <div class="flex justify-center">
        </div>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">{{ props.classroom.name }}</h2>
                <p>{{ props.classroom.subject_code }}</p>
                <div class="card-actions justify-end my-4">
                    <button @click="destroy(props.classroom.id)" type="button" class="btn btn-warning">Delete Class
                    </button>
                    <Link :href="route('classroom.edit',props.classroom.id)" class="btn btn-accent">Edit Class</Link>
                </div>
            </div>
        </div>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <h2 class="card-title">You have {{ props.topic.length }} topic</h2>
                <Link :href="route('topic.create',{classroom_id:props.classroom.id})" class="btn btn-primary">
                    Add Topic
                </Link>
            </div>
        </div>
        <!--        TOPIC-->
        <div v-for="data in props.topic" key="data" class="flex justify-center">
            <div class="card w-3/4 bg-base-100 shadow-xl m-4 w-96">
                <div class="card-body">
                    <h2 class="card-title">{{ data.name }}</h2>
                    <p>{{ data.date_time }}</p>
                    <!--todo: change to actual modules names-->
                    <p>No. of Modules = {{ data.no_of_modules }}</p>
                    <p>expert time = {{ data.max_time_expert }}</p>
                    <p>jigsaw time = {{ data.max_time_jigsaw }}</p>
                    <p>status = {{ data.status }}</p>
                    <div class="card-actions justify-end">
                        <Link :href="route('assessment.index',{topic_id:data.id})" class="btn btn-primary">Add
                            Assessment
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";

const props = defineProps({
    classroom: Object,
    topic: Object,
})

console.log(props.topic.length);
const destroy = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('classroom.destroy', id))
    }
}

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

