<template>

    <Head title="Reset Password"/>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-5xl shadow-xl">
            <div class="grid  md:grid-cols-2 grid-cols-1  bg-base-100 rounded-xl">
                <LandingIntro/>
                <div class="py-24 px-10">
                    <h2 class='text-2xl font-semibold mb-2 text-center'>Reset Password</h2>
                    <form @submit.prevent="submit">
                        <InputText label-title="Email"
                                   input-type="email"
                                   v-model="form.email"
                                   class="my-2"/>
                        <InputText label-title="Password"
                                   input-type="password"
                                   v-model="form.password"
                                   class="my-2"/>
                        <InputText label-title="Password Confirmation"
                                   input-type="password"
                                   v-model="form.password_confirmation"
                                   class="my-2"/>

                        <button class="btn btn-primary float-right my-2"
                                type="submit" :disabled="form.processing">Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";
import InputText from "@/Components/InputText.vue";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
