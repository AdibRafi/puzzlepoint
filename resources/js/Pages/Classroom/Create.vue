<template>
    <Head title="Create Classroom"/>
    <Layout>
        <!--       todo: place the error alert-->
        <form @submit.prevent="form.post(route('classroom.store'));">
            <TitleCard :title="$page.props.auth.user.type === 'lecturer' ?'Create Classroom': 'Join Classroom'">
                <div v-if="wizardStatus === 'onCreateClassroom'"
                     class="alert alert-info shadow-lg">
                    <div>
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" bounce/>
                        <span class="ml-4">Fill in Classroom Name and Subject Code</span>
                    </div>
                </div>
                <div v-if="$page.props.auth.user.type === 'lecturer'">
                    <InputText label-title="Classroom Name" v-model="form.name"/>
                    <InputText label-title="Subject Code" v-model="form.subject_code"/>
                </div>
                <div v-else>
                    <InputText label-title="Classroom Join Code" v-model="form.join_code"/>
                </div>
                <div class="mt-6">
                    <button v-if="wizardStatus !== 'onCreateClassroom'"
                            @click.prevent="router.get(route('classroom.index'))"
                            class="btn btn-accent">Cancel
                    </button>
                    <button type="submit" :disabled="form.processing"
                            class="btn btn-primary float-right">{{usePage().props.auth.user.type === 'lecturer' ? 'Create Classroom' : 'Join Classroom'}}
                    </button>
                </div>
            </TitleCard>
        </form>
    </Layout>
</template>

<script setup>
import {Head, Link, router, useForm, usePage} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";

const form = useForm({
    name: '',
    subject_code: '',
    join_code: '',
})

const wizardStatus = usePage().props.auth.user.wizard_status;
</script>

<style scoped>

</style>
