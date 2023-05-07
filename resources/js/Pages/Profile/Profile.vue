<template>
    <Head title="Profile" />
    <MainLayout>
        <form @submit.prevent="form.patch(route('profile.update'))">
            <Card title="Profile Information">
                <p>Update your account's profile information and email address</p>
                <InputForm label-name="Name" v-model="form.name"/>
                <InputForm label-name="Email Address" v-model="form.email"/>
<!--                todo: perlu bio?-->
<!--                <div class="mt-4">-->
<!--                    <label class="label">-->
<!--                        <span class="label-text text-gray-700 w-3/4">Bio</span>-->
<!--                    </label>-->
<!--                    <textarea class="textarea textarea-bordered w-full textarea-primary"-->
<!--                              placeholder="Bio"></textarea>-->
<!--                </div>-->
                <template #actions>
                    <button class="btn btn-primary">Save</button>
                </template>
            </Card>
            <Card title="Delete Account" class="my-4">
                <p>Once your account id deleted, all of its resources and data will be permanently deleted. Before deleteing your account, please download any data or information that you wish to retain</p>
                <template #actions>
                    <button @click="destroy(form.id)" type="button" class="btn btn-warning">Delete</button>
                </template>
            </Card>


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
import InputForm from "@/Components/InputForm.vue";
import Card from "@/Components/Card.vue";


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
