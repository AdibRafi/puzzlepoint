<template>
    <Head title="Edit Topic"/>
    <Layout page-title="Edit Topic">
        <form @submit.prevent="form.put(route('topic.update',form))">
            <TitleCard :title="props.topic.name"
                       top-right-button-label="Destroy"
                       @button-function="destroy">
                <div class="grid mt-2 md:grid-cols-2 grid-cols-1 gap-6">
                    <InputText label-title="Topic Name" v-model="form.name"/>
                    <InputText label-title="Date" input-type="datetime-local"
                               v-model="form.date_time"/>
                </div>
                <div class="divider"/>
                <button @click.prevent="back" class="btn btn-accent mx-2">Cancel</button>
                <button @click.prevent="duplicateTopic" class="btn btn-primary">Duplicate Topic</button>
                <button type="submit" :disabled="form.processing"
                        class="btn btn-primary float-right mx-2">Update Topic
                </button>
            </TitleCard>
        </form>
    </Layout>
</template>
<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const props = defineProps({
    topic: Object,
})

const form = useForm(props.topic)

const back = () => {
    window.history.back()
}
const destroy = () => {
    if (confirm('Are you sure to delete topic?')) {
        router.delete(route('topic.destroy', props.topic.id));
    } else {
        router.reload();
    }
}
const duplicateTopic = () => {
    if (confirm('Are you sure to Duplicate Topic?')) {
        router.post(route('topic.duplicate',
            {
                topic_id: props.topic.id
            }
        ))
    } else {
        router.reload();
    }
}
</script>
