<template>
    <Head title="Register" />
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-5xl shadow-xl">
            <div class="grid  md:grid-cols-2 grid-cols-1  bg-base-100 rounded-xl">
                <LandingIntro/>
                <div class="py-24 px-10">
                    <h2 class='text-2xl font-semibold mb-2 text-center'>Register</h2>
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <InputText label-title="Name"
                                       container-style="mt-4"
                                       v-model="form.name"/>
                            <InputText label-title="Email"
                                       container-style="mt-4"
                                       v-model="form.email"/>
                            <InputText label-title="Password"
                                       container-style="mt-4"
                                       v-model="form.password"
                            input-type="password"/>
                            <InputText label-title="Password Confirmation"
                                       container-style="mt-4"
                                       v-model="form.password_confirmation"
                            input-type="password"/>
                            <ul class="grid w-full gap-6 md:grid-cols-2 mt-4">
                                <li>
                                    <input type="radio" id="male" name="gender" value="male"
                                           class="hidden peer" v-model="form.gender">
                                    <label for="male"
                                           class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                  <span class="block">
                     <p class="w-full text-lg font-semibold">Male</p>
                  </span>
                                        <font-awesome-icon icon="fa-solid fa-person" size="xl"/>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="female" name="gender" value="female"
                                           class="hidden peer" v-model="form.gender">
                                    <label for="female"
                                           class="inline-flex items-center justify-between w-full p-5 rounded-lg bg-base-100 border border-base-300 cursor-pointer peer-checked:bg-base-300">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">Female</div>
                                        </div>
                                        <font-awesome-icon icon="fa-solid fa-person-dress" size="xl"/>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <ErrorAlert v-if="errors" v-for="error in errors" :key="error"
                                    :error-text="error.valueOf()"/>
                        <button type="submit"
                                :disabled="form.processing"
                                class="btn btn-primary w-full mt-2">Register
                        </button>
                        <div class="text-center mt-4">Already have an account?
                            <Link :href="route('login')">
                        <span
                            class="inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">Login
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
import {Head, Link, useForm} from '@inertiajs/vue3';
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";
import InputText from "@/Components/InputText.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps({
    errors: Object
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
    gender: 'male',

});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
