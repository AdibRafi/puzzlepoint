<template>
    <Head title="Email Verification"/>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-5xl shadow-xl">
            <div class="grid md:grid-cols-2 grid-cols-1 bg-base-100 rounded-xl">
                <LandingIntro/>
                <div class="py-24 px-10">
                    <h2 class="text-2xl font-semibold mb-2 text-center">Thanks For Signing Up!</h2>
                    <p>Before getting started, could you verify your email address by clicking on the link
                        we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
                    <form @submit.prevent="submit">
                        <div class="mt-4 flex items-center justify-between">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">Resend
                                Verification Email
                            </button>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >Log Out
                            </Link
                            >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>
