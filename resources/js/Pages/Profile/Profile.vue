<template>
    <Head title="Profile"></Head>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Profile</h2>
        </template>
        <!--        EMAIL-->
        <div class="card w-1/2 bg-base-100 shadow-xl p-6">
            <h2 class="card-title ">Profile Information</h2>
            <p>Update your account's profile information and email address</p>
            <div class="card-actions">
                <div class="form-control w-full max-w-xs">
                    <div class="mt-4">
                        <label class="label">
                            <span class="label-text text-gray-700">Name</span>
                        </label>
                        <input type="text" placeholder="Type here" class="input input-bordered w-full input-primary"/>
                    </div>
                    <div class="mt-4">
                        <label class="label">
                            <span class="label-text text-gray-700">Email Address</span>
                        </label>
                        <input type="text" placeholder="Type here" class="input input-bordered w-full input-primary"/>
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
<!--        todo: do backend of delete account-->
        <div class="card w-1/2 bg-base-100 shadow-xl p-6 my-6">
            <h2 class="card-title ">Delete Account</h2>
            <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
            <div class="card-actions my-4">
                <button class="btn btn-error">Delete</button>
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
    </MainLayout>
</template>

<script setup>
// import '../js/bootstrap'
import {onMounted, ref} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from "@inertiajs/vue3";

const profileData = ref()


// todo: find a way to authenticate user and use only that to display profile
// onMounted(() => {
//     axios.get("http://jigsawprototype.test/api/profile")
//         .then((res) => {
//             console.log(res.data.datas)
//             profileData.value = res.data.datas
//
//         })
//         .catch((err) =>
//             console.log(err.data.datas));
// })
</script>

<style scoped>

</style>
