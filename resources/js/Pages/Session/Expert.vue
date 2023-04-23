<template>
    <Head title="Expert"/>
    <MainLayout>
        <Card title="DEVELOPER">
            <button class="btn btn-primary" @click="buttonTest">Reload</button>
        </Card>
        <Card title="Expert Session" class="my-4">
            <p>{{ props.topicModuleModal.name }}</p>
        </Card>
        <Card title="Timer" class="my-4">
            <p class="justify-center flex text-2xl">{{minuteCounter}}:{{secondCounter}}</p>
        </Card>
        <div v-for="groupData in props.expertGroupUserModal">
            <Card :title="groupData.name" class="my-4">
                <div v-for="userData in groupData.users">
                    <p>{{ userData.name }}</p>
                </div>
            </Card>
        </div>
        <Card>
            <Link :href="route('session.jigsaw',
            {topic_id:props.topicModuleModal.id})"
                  class="btn btn-primary">
                Next to Jigsaw
            </Link>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    topicModuleModal: Object,
    studentAttendModal: Object,
    studentAbsentModal: Object,
    expertGroupUserModal: Object,

})

const minuteCounter = ref(props.topicModuleModal.max_time_expert);
const secondCounter = ref(0);

const buttonTest = () => {
    console.log('nice');
    router.reload();
}

setInterval(() => {
    if (secondCounter.value > 0) {
        secondCounter.value--;
    } else {
        secondCounter.value = 59;
        minuteCounter.value--;
    }
}, 1000)
</script>

<style scoped>

</style>
