<template>
    <Head title="Edit Assessment"/>
    <Layout page-title="Edit">
        <TitleCard title="Edit Assessment">
            <GridLayout>
                <InputText label-title="Duration for the Assessment"
                           input-type="number"
                           v-model="form.time"/>
                <InputText label-title="End Date" input-type="datetime-local"
                           v-model="form.endPublish"/>
            </GridLayout>
            <button class="btn btn-primary mt-8 float-right"
                    @click.prevent="submit">Update
            </button>
        </TitleCard>
    </Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import GridLayout from "@/Components/GridLayout.vue";
import InputText from "@/Components/InputText.vue";
import {Head, router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    assessment: Object,
})
const form = useForm({
    time: props.assessment.time,
    endPublish: props.assessment.publish_end
});


const dateTime = new Date(props.assessment.publish_end);
// console.log(dateTime.toISOString());
// console.log(dateTime.toISOString().slice(0, 16));

const submit = () => {
    form.endPublish = new Date(form.endPublish).toISOString().slice(0, 16)
    router.put(route('assessment.update', props.assessment), form.data());
}
</script>
