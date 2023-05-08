<template>
    <Head title="Test"/>
    <MainLayout>
        <form @submit.prevent="form.post(route('test.store'))">
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Card title!</h2>
                    <p>If a dog chews shoes whose shoes does he choose?</p>
                    <div class="card-actions justify-end">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Store</button>
                    </div>
                </div>
            </div>
        </form>
        <form @submit.prevent="form2.post(route('send.expert',{topic_id:1}));">
            <div class="card w-96 bg-red-200 shadow-xl my-4">
                <div class="card-body">
                    <div class="card-actions justify-end">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Send Broadcast
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="card w-96 bg-base-100 shadow-xl my-6">
            <div class="card-body">
                <h2 class="card-title">{{ props.data }}</h2>
            </div>
        </div>
        <!--        <div v-for="data in data">-->
        <!--            <div class="card w-96 bg-base-100 shadow-xl my-6">-->
        <!--                <div class="card-body">-->
        <!--                    <h2 class="card-title">{{ data.name }}</h2>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <Card>
<!--            <VuePdfEmbed source="modules/M1.pdf"/>-->
        </Card>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {onMounted} from "vue";
import Card from "@/Components/Card.vue";
import VuePdfEmbed from "vue-pdf-embed";


const tmDummy = {
    '1': '15',
    '2': '15',
    '3': '15',
    '4': '15',
}

const props = defineProps({
    data: Object,
})

const form = useForm({
    topic_id: 1,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: tmDummy,
});

const form2 = useForm({
    topic_id: 1,

})

onMounted(() => {
    console.log('mounted')
});

</script>

<style scoped>

</style>
