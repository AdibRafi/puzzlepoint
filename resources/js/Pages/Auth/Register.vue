<template>
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
                                       v-model="form.password"/>
                            <InputText label-title="Password Confirmation"
                                       container-style="mt-4"
                                       v-model="form.password_confirmation"/>
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
    <!--   <GuestLayout>-->
    <!--      <Head title="Register" />-->

    <!--      <form @submit.prevent="submit">-->
    <!--         <div>-->
    <!--            <InputLabel for="name" value="Name" />-->

    <!--            <TextInput-->
    <!--               id="name"-->
    <!--               type="text"-->
    <!--               class="mt-1 block w-full"-->
    <!--               v-model="form.name"-->
    <!--               required-->
    <!--               autofocus-->
    <!--               autocomplete="name"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.name" />-->
    <!--         </div>-->

    <!--         <div class="mt-4">-->
    <!--            <InputLabel for="email" value="Email" />-->

    <!--            <TextInput-->
    <!--               id="email"-->
    <!--               type="email"-->
    <!--               class="mt-1 block w-full"-->
    <!--               v-model="form.email"-->
    <!--               required-->
    <!--               autocomplete="username"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.email" />-->
    <!--         </div>-->

    <!--         <div class="mt-4">-->
    <!--            <InputLabel for="password" value="Password" />-->

    <!--            <TextInput-->
    <!--               id="password"-->
    <!--               type="password"-->
    <!--               class="mt-1 block w-full"-->
    <!--               v-model="form.password"-->
    <!--               required-->
    <!--               autocomplete="new-password"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.password" />-->
    <!--         </div>-->

    <!--         <div class="mt-4">-->
    <!--            <InputLabel for="password_confirmation" value="Confirm Password" />-->

    <!--            <TextInput-->
    <!--               id="password_confirmation"-->
    <!--               type="password"-->
    <!--               class="mt-1 block w-full"-->
    <!--               v-model="form.password_confirmation"-->
    <!--               required-->
    <!--               autocomplete="new-password"-->
    <!--            />-->

    <!--            <InputError class="mt-2" :message="form.errors.password_confirmation" />-->
    <!--         </div>-->

    <!--         <div class="flex items-center justify-end mt-4">-->
    <!--            <Link-->
    <!--               :href="route('login')"-->
    <!--               class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"-->
    <!--            >-->
    <!--               Already registered?-->
    <!--            </Link>-->

    <!--            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
    <!--               Register-->
    <!--            </PrimaryButton>-->
    <!--         </div>-->
    <!--      </form>-->
    <!--   </GuestLayout>-->
</template>

<script setup>
import GuestLayout from '@/Layouts/Archives/GuestLayout.vue';
import InputError from '@/Components/Archives/InputError.vue';
import InputLabel from '@/Components/Archives/InputLabel.vue';
import PrimaryButton from '@/Components/Archives/PrimaryButton.vue';
import TextInput from '@/Components/Archives/TextInput.vue';
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
