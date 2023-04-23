<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <button class="btn btn-primary" @click="buttonTest">Reload</button>
        </Card>
        <Card title="Jigsaw Session" class="my-4">
            <p>{{ props.topicModuleModal.name }}</p>
        </Card>
        <Card title="Timer" class="my-4">
            <p class="justify-center flex text-2xl">{{minuteCounter}}:{{secondCounter}}</p>
        </Card>
        <div v-for="groupData in props.jigsawGroupUserModal">
            <Card :title="groupData.name" class="my-4">
                <div v-for="userData in groupData.users">
                    <p>{{ userData.name }}</p>
                </div>
            </Card>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    topicModuleModal: Object,
    jigsawGroupUserModal: Object,

})

const minuteCounter = ref(props.topicModuleModal.max_time_jigsaw);
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
