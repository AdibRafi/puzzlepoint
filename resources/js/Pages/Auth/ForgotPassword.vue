<template>
   <Head title="Forgot Password" />
   <div class="min-h-screen bg-base-200 flex items-center">
      <div class="card mx-auto w-full max-w-5xl shadow-xl">
         <div class="grid md:grid-cols-2 grid-cols-1 bg-base-100 rounded-xl">
            <LandingIntro/>
            <div class="py-24 px-10">
               <h2 class="text-2xl font-semibold mb-2 text-center">Forgot Password</h2>
               <div class="text-center mt-8" v-if="status">
                  <font-awesome-icon
                     icon="fa-solid fa-circle-check"
                     style="color: #36d399;"
                     class="object-cover h-28"/>
                  <p class="mt-4 mb-8 font-semibold">Link Sent</p>
                  <p class='mt-4 mb-8 font-semibold text-center'>Check your email to reset password</p>
                  <div class='text-center mt-4'>
                     <Link :href="route('login')">
                        <button class="btn btn-block btn-primary ">Login</button>
                     </Link>
                  </div>
               </div>
               <div v-else>
                  <p class="my-8 font-semibold text-center">We will send password reset link on your Email ID</p>
                  <form @submit.prevent="submit">
                     <div class="mb-4">
                        <InputText type="email"
                                   container-style="border-none bg-base-100"
                                   label-title="Email"
                                   v-model="form.email"/>
                        <ErrorAlert v-if="errors" v-for="error in errors" :key="error"
                                    :error-text="error.valueOf()"/>
                        <button @submit.prevent
                                type="submit"
                                class="btn btn-primary w-full mt-2">
                           Send Reset
                        </button>
                        <div class="text-center mt-4">
                           Don't have an account yet?
                           <Link :href="route('register')">
                              <button
                                 class="inline-block hover:text-primary hover:underline hover:cursor-pointer transition duration-200">
                                 Register
                              </button>
                           </Link>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>


<script setup>
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";
import InputText from "@/Components/InputText.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";

defineProps({
   status: String,
   errors: Object
});

const form = useForm({
   email: '',
});

const submit = () => {
   form.post(route('password.email'));
};
</script>
