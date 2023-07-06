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

    <!--    <form @submit.prevent="submit">-->
    <!--        <div>-->
    <!--            <InputLabel for="email" value="Email"/>-->

    <!--            <TextInput-->
    <!--                id="email"-->
    <!--                type="email"-->
    <!--                class="mt-1 block w-full"-->
    <!--                v-model="form.email"-->
    <!--                required-->
    <!--                autofocus-->
    <!--                autocomplete="username"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.email"/>-->
    <!--        </div>-->

    <!--        <div class="mt-4">-->
    <!--            <InputLabel for="password" value="Password"/>-->

    <!--            <TextInput-->
    <!--                id="password"-->
    <!--                type="password"-->
    <!--                class="mt-1 block w-full"-->
    <!--                v-model="form.password"-->
    <!--                required-->
    <!--                autocomplete="new-password"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.password"/>-->
    <!--        </div>-->

    <!--        <div class="mt-4">-->
    <!--            <InputLabel for="password_confirmation" value="Confirm Password"/>-->

    <!--            <TextInput-->
    <!--                id="password_confirmation"-->
    <!--                type="password"-->
    <!--                class="mt-1 block w-full"-->
    <!--                v-model="form.password_confirmation"-->
    <!--                required-->
    <!--                autocomplete="new-password"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.password_confirmation"/>-->
    <!--        </div>-->

    <!--        <div class="flex items-center justify-end mt-4">-->
    <!--            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
    <!--                Reset Password-->
    <!--            </PrimaryButton>-->
    <!--        </div>-->
    <!--    </form>-->
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
