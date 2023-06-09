<template>
    <Head title="Verify 2FA"/>
    <div class="min-h-screen bg-base-200 flex items-center">
        <div class="card mx-auto w-full max-w-5xl shadow-xl">
            <div class="grid md:grid-cols-2 grid-cols-1 bg-base-100 rounded-xl">
                <LandingIntro/>
                <div class="py-24 px-10">
                    <h2 class="text-2xl font-semibold mb-2 text-center">Verify 2FA</h2>
                    <div>
                        <p class="my-8 font-semibold text-center">We sent you the code, Check your mobile</p>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputText container-style="border-none bg-base-100"
                                           label-title="Code"
                                           v-model="form.code"/>
                                <ErrorAlert v-if="errors" v-for="error in props.errors" :key="error"
                                            :error-text="error.valueOf()"/>
                                <button @submit.prevent
                                        type="submit"
                                        class="btn btn-primary w-full mt-4">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ErrorAlert from "@/Components/ErrorAlert.vue";
import {Head, Link, router, useForm} from "@inertiajs/vue3";
import LandingIntro from "@/Pages/Welcome/LandingIntro.vue";
import InputText from "@/Components/InputText.vue";
import {ref} from "vue";

const props = defineProps({
    errors: Object,
    user: Object,

})

const code = ref();

const form = useForm({
    email: props.user.name,
    password: props.user.password,
    code: '',
})
const submit = () => {
    form.post(route('check.2fa'))
}
</script>
