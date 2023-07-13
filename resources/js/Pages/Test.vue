<template>
    <Head title="Test"/>
    <Layout page-title="Test Page">
        <Card title="DEVELOPER"/>

        <!--        <TitleCard>-->
        <!--        </TitleCard>-->

        <!--        <Card v-for="classroomData in usePage().props.auth.classrooms" :key="classroomData">-->
        <!--            <p>{{ classroomData.name }}</p>-->
        <!--        </Card>-->
        <TitleCard>
            <TimeCalculator number-of-modules="4"
                            number-of-students="100"
                            time-method="uneven"
                            :modules-data="props.moduleData"
                            @out-data="getTime"/>
            <!--            <iframe src="modules/M1.pdf" type="application/pdf"-->
<!--                    width="100%" height="800"/>-->

        </TitleCard>
        <!--        <div class="h-full carousel carousel-vertical rounded-box">-->
<!--            <div v-for="data in 4" class="carousel-item h-full">-->
<!--                <vue-pdf-embed source="modules/M1.pdf" :page="data"-->
<!--                               width="800"/>-->
<!--            </div>-->
<!--        </div>-->

        <!--                <div class="carousel flex flex-wrap">-->
        <!--                    <div id="slide1" class="carousel-item relative w-full">-->
        <!--                        <vue-pdf-embed source="modules/M1.pdf" :page="1"-->
        <!--                                       width="800"/>-->
        <!--                        &lt;!&ndash;               <embed src="/modules/M1.pdf" class="w-full" />&ndash;&gt;-->
        <!--                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">-->
        <!--                            <a href="#slide4" class="btn btn-circle">❮</a>-->
        <!--                            <a href="#slide2" class="btn btn-circle">❯</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <div id="slide2" class="carousel-item relative w-full">-->
        <!--                        <vue-pdf-embed source="modules/M1.pdf" :page="2"-->
        <!--                                       width="800"/>-->
        <!--                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">-->
        <!--                            <a href="#slide1" class="btn btn-circle">❮</a>-->
        <!--                            <a href="#slide3" class="btn btn-circle">❯</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <div id="slide3" class="carousel-item relative w-full">-->
        <!--                        <vue-pdf-embed source="modules/M1.pdf" :page="3"-->
        <!--                                       width="800"/>-->
        <!--                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">-->
        <!--                            <a href="#slide2" class="btn btn-circle">❮</a>-->
        <!--                            <a href="#slide4" class="btn btn-circle">❯</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <div id="slide4" class="carousel-item relative w-full">-->
        <!--                        <vue-pdf-embed source="modules/M1.pdf" :page="4"-->
        <!--                                       width="800"/>-->
        <!--                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">-->
        <!--                            <a href="#slide3" class="btn btn-circle">❮</a>-->
        <!--                            <a href="#slide1" class="btn btn-circle">❯</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--        <Card>-->
        <!--            <button type="submit"-->
        <!--                    @click.prevent="router.get(route('sms.test'))"-->
        <!--                    class="btn btn-primary">-->
        <!--                SMS test-->
        <!--            </button>-->
        <!--        </Card>-->
        <!--        <Card>-->
        <!--            <button @click.prevent="router.get(route('migrate.group'),{-->
        <!--                seeder:'GroupSeeder'-->
        <!--            })" class="btn btn-secondary">MIGRATE-->
        <!--            </button>-->
        <!--        </Card>-->
    </Layout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import {onMounted, reactive, ref} from "vue";
import Card from "@/Components/Card.vue";
import VuePdfEmbed from "vue-pdf-embed";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import {useStore} from 'vuex'
import InputText from "@/Components/InputText.vue";
import TimeCalculator from "@/Components/TimeCalculator.vue";

const messages = reactive([])
const inputMessage = ref(null);
const addMessage = () => {
    messages.push({
        name: 'adib',
        messageInput: inputMessage.value
    })
}

const getTime = (value) => {
    console.log(value)
}

const tmDummy = {
    '1': '15',
    '2': '15',
    '3': '15',
    '4': '15',
}

const props = defineProps({
    data: Object,
    moduleData: Object,

})

const form = useForm({
    topic_id: 1,
    groupMethod: 'none',
    timeMethod: 'even',
    tm: tmDummy,
});


onMounted(() => {
    console.log('mounted')
});

</script>

<style scoped>

</style>
