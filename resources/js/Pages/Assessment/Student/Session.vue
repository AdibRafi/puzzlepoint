<template>
    <MainLayout>
        <Card title="DEVELOPER">
            <!--            <p>{{props.questionAnswerModal}}</p>-->
        </Card>
        <Card :title="'Question ' + (Index+1)"
              v-for="(quesData,Index) in props.questionAnswerModal"
              :key="quesData">
            <p>{{quesData.name}}</p>
            <div v-for="ansData in quesData.answers" :key="ansData">
                <div v-if="quesData.type === 'radio'">
                    <label class="label cursor-pointer">
                        <input type="radio" name="radioAns" class="mr-2 radio" :value="ansData"
                        >
                        <p>{{ansData.name}}</p>
                    </label>
                </div>
                <div v-if="quesData.type === 'check'">
                    <label class="label cursor-pointer">
                        <input type="checkbox" name="checkAns" class="mr-2 checkbox" :value="ansData">
                        <p>{{ansData.name}}</p>
                    </label>
                </div>
            </div>
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    topicModal: Object,
    questionAnswerModal: Object,
    assessmentModal: Object,
});
</script>

<style scoped>

</style>
