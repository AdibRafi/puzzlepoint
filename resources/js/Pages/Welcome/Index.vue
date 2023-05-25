<template>
    <Head title="Login"/>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-5xl shadow-xl">
            <div class="grid md:grid-cols-2 grid-cols-1 bg-base-100 rounded-xl">
                <LandingIntro/>
                <div class="py-24 px-10">
                    <h2 class="text-2xl font-semibold mb-2 text-center">Login</h2>
                    <form @submit.prevent="form.post(route('login.store'))">
                        <div class="mb-4">
                            <InputText input-type="email"
                                       container-style="mt-4"
                                       label-title="Email"
                                       v-model="form.email"/>
                            <InputText input-type="password"
                                       container-style="mt-4"
                                       label-title="Password"
                                       v-model="form.password"/>
                        </div>
                        <div class="text-right text-primary">
                            <Link :href="route('password.request')">
                                <span
                                    class="text-sm inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">
                                    Forgot Password?
                                </span>
                            </Link>
                        </div>
                        <ErrorAlert v-if="props.errors"
                                    v-for="error in props.errors"
                                    :key="error"
                                    :error-text="error.valueOf()"
                                    class="mt-2"/>
                        <button type="submit"
                                class="btn btn-primary mt-2 w-full"
                                @submit.prevent>
                            Login
                        </button>
                        <div class="text-center mt-4">
                            Don't have an account yet?
                            <Link :href="route('register')">
                                <span
                                    class="inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">
                                    Register
                                </span>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";
import InputText from "@/Components/InputText.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import ErrorAlert from "@/Components/ErrorAlert.vue";
import Header from "@/Layouts/Header.vue";

const props = defineProps({
    errors: Object,
})

const form = useForm({
    email: '',
    password: '',
})
</script>
