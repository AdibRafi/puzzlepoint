<template>
    <Head title="Profile"></Head>
    <MainLayout>
        <form @submit.prevent="form.patch(route('profile.update'))" class="flex flex-col items-center">
            <div class="card w-1/2 bg-base-100 shadow-xl p-6">
                <h2 class="card-title ">Profile Information</h2>
                <p>Update your account's profile information and email address</p>
                <div class="card-actions">
                    <div class="form-control w-full max-w-xs">
                        <div class="mt-4">
                            <InputForm label-name="Name" v-model="form.name"/>
                        </div>
                        <div class="mt-4">
                            <InputForm label-name="Email Address" v-model="form.email"/>
                        </div>
                        <div class="mt-4">
                            <label class="label">
                                <span class="label-text text-gray-700 w-3/4">Bio</span>
                            </label>
                            <textarea class="textarea textarea-bordered w-full textarea-primary"
                                      placeholder="Bio"></textarea>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary w-1/2">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-1/2 bg-base-100 shadow-xl p-6 my-6">
                <h2 class="card-title ">Delete Account</h2>
                <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before
                    deleting your account, please download any data or information that you wish to retain.</p>
                <div class="card-actions my-4">
                    <button @click="destroy(form.id)" type="button" class="btn btn-warning">Delete</button>
                </div>
            </div>


            <!--        todo: (x priority) if nk buat update password thing-->

            <!--        <div class="card w-96 bg-base-100 shadow-xl my-10">-->
            <!--            <div class="card-body">-->
            <!--                <h2 class="card-title text-2xl">Profile</h2>-->
            <!--                <div class="my-2">-->
            <!--                    <p>Name</p>-->
            <!--                    <p>adib</p>-->
            <!--                </div>-->
            <!--                <div class="my-2">-->
            <!--                    <p>Email</p>-->
            <!--                    <p>email adib</p>-->
            <!--                </div>-->
            <!--                <div class="my-2">-->
            <!--                    <p>Bio</p>-->
            <!--                    <p>This is my Bio for this PuzzlePoint!</p>-->
            <!--                </div>-->
            <!--                <div class="card-actions justify-center flex space-x-4 my-2">-->
            <!--                    &lt;!&ndash;                <router-link :to="{name:'editProfile'}">&ndash;&gt;-->
            <!--                    <button class="btn btn-primary">Edit</button>-->
            <!--                    &lt;!&ndash;                </router-link>&ndash;&gt;-->
            <!--                    <button class="btn btn-primary">Delete</button>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
        </form>
    </MainLayout>
</template>

<script setup>
// import '../js/bootstrap'
import {onMounted, ref} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import InputForm from "@/Components/inputForm.vue";


const user = usePage().props.auth.user;

const form = useForm({
    id: user.id,
    name: user.name,
    email: user.email,

});

const destroy = (id) => {
    if (confirm('Are you sure to delete account?')) {
        router.delete(route('profile.destroy', id));
    }
}

</script>

<style scoped>

</style>
