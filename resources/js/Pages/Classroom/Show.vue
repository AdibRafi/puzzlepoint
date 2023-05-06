<template>
    <Head :title="props.classroom.name"/>
    <MainLayout>
        <Card :title="props.classroom.name">
            <p>{{ props.classroom.subject_code }}</p>
            <template #actions v-if="$page.props.user.type === 'lecturer'">
                <button @click="destroyClassroom(props.classroom.id)" type="button" class="btn btn-warning">Delete Class
                </button>
                <Link :href="route('classroom.edit',props.classroom.id)" class="btn btn-accent">Edit Class</Link>
            </template>
        </Card>
        <div v-if="$page.props.user.type === 'lecturer'">
            <Card :title="'You have '+props.topicModal.length + ' topic'">
                <Link :href="route('topic.create',{classroom_id:props.classroom.id})" class="btn btn-primary">
                    Add Topic
                </Link>
            </Card>
        </div>
        <!--        TOPIC-->
        <div v-for="data in props.topicModal" :key="data" class="my-4">
            <Card :title="data.name">
                <p>{{ data.date_time }}</p>
                <!--todo: change to actual modules names-->
                <p>No. of Modules = {{ data.no_of_modules }}</p>
                <p>expert time = {{ data.max_time_expert }}</p>
                <p>jigsaw time = {{ data.max_time_jigsaw }}</p>
                <!--todo: do dropdown for module option etc-->
                <template #actions>
                    <div v-if="$page.props.user.type ==='lecturer'">
                        <div class="dropdown dropdown-hover">
                            <label tabindex="0" class="btn m-1">Edit</label>
                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li>
                                    <Link :href="route('module.editIndex',{topic_id:data.id})">Module</Link>
                                </li>
                                <li>
                                    <Link :href="route('option.editIndex',{
                                topic_id:data.id,
                            })">Option
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div v-if="data.is_ready === 1">
                            <Link :href="route('lecturer.session.index',{topic_id: data.id})" class="btn btn-primary">
                                Start
                            </Link>
                        </div>
                        <button @click="destroyTopic(data.id)" type="button" class="btn btn-warning">Delete
                        </button>
                        <Link :href="route('assessment.index',{topic_id:data.id})" class="btn btn-primary">
                            Assessment
                        </Link>

                    </div>
                    <div v-if="$page.props.user.type === 'student'">
                        <Link :href="route('student.session.index',{topic_id: data.id})" class="btn btn-secondary">
                            student
                            start session
                        </Link>
                        <Link :href="route('student.assessment.index',{topic_id:data.id})"
                              class="btn btn-accent">
                            Student Assessment
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
    topicModal: Object,
})

// console.log(props.topicModal.length);
const destroyClassroom = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('classroom.destroy', id))
    }
}

const destroyTopic = (id) => {
    if (confirm('Are you sure to delete?')) {
        router.delete(route('topic.destroy', id));
    }
}

</script>

