<template>
    <MainLayout>
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title justify-center text-2xl">Expert Session</h2>
            </div>
        </div>
        <TimerDisplay :max_time_expert="props.topicModal.max_time_expert"/>
        <div class="card w-96 bg-base-100 shadow-xl my-4">
            <div class="card-body">
                <h2 class="card-title">Your Group</h2>
                <div class="my-4">
                    <div v-for="userData in props.userModal">
                        <p>{{ userData.name }}</p>
                    </div>
                    <p class="my-10">{{ props.topicModal }}</p>
                </div>
            </div>
        </div>
        <!--    <div>-->
        <!--        <embed src="public/pdf/testPDF.pdf" width="800" height="900">-->
        <!--    </div>-->
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TimerDisplay from "@/Components/timerDisplay.vue";

const props = defineProps({
    topicModal: Object,
    groupModal: Object,
    userModal: Object,
})

</script>

<style scoped>

</style>
