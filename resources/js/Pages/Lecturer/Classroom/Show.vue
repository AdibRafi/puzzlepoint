<template>
    <!--    todo: Head - change to class name-->
    <Head :title="props.classroom.name"/>
    <MainLayout>
        <Card :title="props.classroom.name">
            <p>{{ props.classroom.subject_code }}</p>
            <template #actions v-if="$page.props.user.type === 'lecturer'">
                <button @click="destroy(props.classroom.id)" type="button" class="btn btn-warning">Delete Class
                </button>
                <Link :href="route('classroom.edit',props.classroom.id)" class="btn btn-accent">Edit Class</Link>
            </template>
        </Card>
        <div v-if="$page.props.user.type === 'lecturer'" class="card w-96 bg-base-100 shadow-xl my-4">
            <Card :title="'You have '+props.topic.length + ' topic'">
                <Link :href="route('topic.create',{classroom_id:props.classroom.id})" class="btn btn-primary">
                    Add Topic
                </Link>
            </Card>
        </div>
        <!--        TOPIC-->
        <div v-for="data in props.topic" :key="data" class="my-4">
            <Card :title="data.name">
                <p>{{ data.date_time }}</p>
                <!--todo: change to actual modules names-->
                <p>No. of Modules = {{ data.no_of_modules }}</p>
                <p>expert time = {{ data.max_time_expert }}</p>
                <p>jigsaw time = {{ data.max_time_jigsaw }}</p>
                <!--                todo: do dropdown for module option etc-->
                <template #actions>
                    <div v-if="$page.props.user.type ==='lecturer'">
                        <div v-if="data.status === 'onModule'">
                            <Link
                                :href="route('module.create',{
                                topic_id: data.id,
                                no_of_modules:data.no_of_modules
                            })"
                                class="btn btn-primary">Module
                            </Link>
                        </div>
                        <div v-else-if="data.status === 'onOption'">
                            <Link :href="route('option.create',{
                            topic_id:data.id,
                            no_of_modules:data.no_of_modules
                        })" class="btn btn-primary">Option
                            </Link>
                        </div>
                        <div v-else-if="data.status === 'onVerify'">
                            <Link :href="route('topic.verify',{
                            topic_id: data.id
                        })" class="btn btn-primary">
                                Verify
                            </Link>
                        </div>
                        <div v-else-if="data.status === 'onReady'">
                            <Link :href="route('')" class="btn btn-primary">Start</Link>
                        </div>
                        <Link :href="route('assessment.index',{topic_id:data.id})" class="btn btn-primary">
                            Assessment
                        </Link>
                    </div>
                    <div v-if="$page.props.user.type === 'student'">
                        <Link :href="route('student.expert',{topic_id: data.id})" class="btn btn-secondary">student
                            start session
                        </Link>
                    </div>
                </template>
            </Card>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";

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

</script>

