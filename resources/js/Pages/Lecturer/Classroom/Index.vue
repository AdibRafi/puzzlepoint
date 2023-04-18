<template>
    <Head title="Dashboard"/>
    <MainLayout>
        <div v-if="$page.props.user.type === 'lecturer'" class="my-2 mx-6 mb-4">
            <Card :title="'Total Classroom = ' + props.classroomData.length">
                <template #actions>
                    <Link :href="route('classroom.create')">
                        <button class="btn btn-primary">Add Class</button>
                    </Link>
                </template>
            </Card>
        </div>
        <div v-for="data in classroomData" key="data" class="flex justify-center">
            <Card :title="data.name">
                <p>{{ data.subject_code }}</p>
                <template #actions>
                    <Link :href="route('classroom.show',data.id)" class="btn btn-primary">Enter</Link>
                </template>
            </Card>
        </div>
    </MainLayout>

</template>


<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    classroomData: Object,
})

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

<style scoped>

</style>
