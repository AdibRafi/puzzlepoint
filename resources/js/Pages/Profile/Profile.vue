<template>
    <Head title="Profile"/>
    <Layout page-title="Settings">
        <TitleCard title="Profile Settings" top-margin="mt-2">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InputText label-title="Name" v-model="form.name"/>
                <InputText label-title="Email" v-model="form.email"/>
                <!--            todo: need bio?-->
                <TextAreaInput label-title="bio" v-model="form.bio"/>
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text text-base-content">Phone</span>
                    </label>
                    <input v-maska
                           v-model="form.phone_number"
                           data-maska="+60 ##-### ####"
                           class="input input-bordered w-full">
                </div>
            </div>
            <div class="divider"/>
            <div v-if="user.phone_number === null">

                <!--todo: put icon (make it nice)-->



                <font-awesome-icon icon="fa-solid fa-user-secret" size="xl"/>
                <div class="alert inline-block">
                    <font-awesome-icon icon="fa-solid fa-circle-exclamation"
                                       class="inline-block"/>
                    <span
                        class="inline-block ml-4">If you want Two Factor Authorization, Fill in the phone number first.</span>
                </div>
            </div>
            <div v-else>
                <Card>
                    <InputText label-title="Verify Code" v-model="verifyCodeInput"/>
                    <template #actions>
                        <button @click.prevent="verifyCode"
                                class="btn btn-primary float-right">
                            Verify
                        </button>
                        <button class="btn btn-primary"
                                @click.prevent="router.get(route('sms.test'))">
                            Send Code
                        </button>
                    </template>
                </Card>
            </div>
            <div class="divider"/>
            <select class="select select-bordered mr-4" data-choose-theme>
                <option disabled selected>Theme</option>
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="corporate">Corporate</option>
                <option value="business">Business</option>
                <option value="emerald">Emerald</option>
            </select>
            <div class="mt-10">
                <button @click.prevent="updateProfile"
                        class="btn btn-primary float-right">
                    Update
                </button>
            </div>
        </TitleCard>
    </Layout>
    <!--    <MainLayout>-->
    <!--        <form @submit.prevent="form.patch(route('profile.update'))">-->
    <!--            <Card title="Profile Information">-->
    <!--                <p>Update your account's profile information and email address</p>-->
    <!--                <InputForm label-name="Name" v-model="form.name"/>-->
    <!--                <InputForm label-name="Email Address" v-model="form.email"/>-->
    <!--&lt;!&ndash;                todo: perlu bio?&ndash;&gt;-->
    <!--&lt;!&ndash;                <div class="mt-4">&ndash;&gt;-->
    <!--&lt;!&ndash;                    <label class="label">&ndash;&gt;-->
    <!--&lt;!&ndash;                        <span class="label-text text-gray-700 w-3/4">Bio</span>&ndash;&gt;-->
    <!--&lt;!&ndash;                    </label>&ndash;&gt;-->
    <!--&lt;!&ndash;                    <textarea class="textarea textarea-bordered w-full textarea-primary"&ndash;&gt;-->
    <!--&lt;!&ndash;                              placeholder="Bio"></textarea>&ndash;&gt;-->
    <!--&lt;!&ndash;                </div>&ndash;&gt;-->
    <!--                <template #actions>-->
    <!--                    <button class="btn btn-primary">Save</button>-->
    <!--                </template>-->
    <!--            </Card>-->
    <!--            <Card title="Delete Account" class="my-4">-->
    <!--                <p>Once your account id deleted, all of its resources and data will be permanently deleted. Before deleteing your account, please download any data or information that you wish to retain</p>-->
    <!--                <template #actions>-->
    <!--                    <button @click="destroy(form.id)" type="button" class="btn btn-warning">Delete</button>-->
    <!--                </template>-->
    <!--            </Card>-->


    <!--            &lt;!&ndash;        todo: (x priority) if nk buat update password thing&ndash;&gt;-->

    <!--            &lt;!&ndash;        <div class="card w-96 bg-base-100 shadow-xl my-10">&ndash;&gt;-->
    <!--            &lt;!&ndash;            <div class="card-body">&ndash;&gt;-->
    <!--            &lt;!&ndash;                <h2 class="card-title text-2xl">Profile</h2>&ndash;&gt;-->
    <!--            &lt;!&ndash;                <div class="my-2">&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>Name</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>adib</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                </div>&ndash;&gt;-->
    <!--            &lt;!&ndash;                <div class="my-2">&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>Email</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>email adib</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                </div>&ndash;&gt;-->
    <!--            &lt;!&ndash;                <div class="my-2">&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>Bio</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <p>This is my Bio for this PuzzlePoint!</p>&ndash;&gt;-->
    <!--            &lt;!&ndash;                </div>&ndash;&gt;-->
    <!--            &lt;!&ndash;                <div class="card-actions justify-center flex space-x-4 my-2">&ndash;&gt;-->
    <!--            &lt;!&ndash;                    &lt;!&ndash;                <router-link :to="{name:'editProfile'}">&ndash;&gt;&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <button class="btn btn-primary">Edit</button>&ndash;&gt;-->
    <!--            &lt;!&ndash;                    &lt;!&ndash;                </router-link>&ndash;&gt;&ndash;&gt;-->
    <!--            &lt;!&ndash;                    <button class="btn btn-primary">Delete</button>&ndash;&gt;-->
    <!--            &lt;!&ndash;                </div>&ndash;&gt;-->
    <!--            &lt;!&ndash;            </div>&ndash;&gt;-->
    <!--            &lt;!&ndash;        </div>&ndash;&gt;-->
    <!--        </form>-->
    <!--    </MainLayout>-->
</template>

<script setup>
// import '../js/bootstrap'
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import TitleCard from "@/Components/TitleCard.vue";
import InputText from "@/Components/InputText.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import {vMaska} from "maska";
import Card from "@/Components/Card.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onMounted, onUpdated, ref} from "vue";

const user = usePage().props.auth.user;
const verifyCodeInput = ref();

const form = useForm({
    id: user.id,
    name: user.name,
    email: user.email,
    phone_number: user.phone_number,
    bio: user.bio,
});

const destroy = (id) => {
    if (confirm('Are you sure to delete account?')) {
        router.delete(route('profile.destroy', id));
    }
}

const updateProfile = () => {
    form.phone_number = form.phone_number.replaceAll('+', '')
        .replaceAll(' ', '')
        .replaceAll('-', '');
    router.patch(route('profile.update', form))
}

const verifyCode = () => {
    router.get(route('verify.2fa'), {
        code: verifyCodeInput.value
    })
}

</script>

<style scoped>

</style>
