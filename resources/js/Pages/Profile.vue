<template>
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl">Profile</h2>
            <div class="my-2">
                <p>Name</p>
                <p>adib</p>
            </div>
            <div class="my-2">
                <p>Email</p>
                <p>email adib</p>
            </div>
            <div class="my-2">
                <p>Bio</p>
                <p>This is my Bio for this PuzzlePoint!</p>
            </div>
            <div class="card-actions justify-center flex space-x-4 my-2">
                <!--                <router-link :to="{name:'editProfile'}">-->
                <button class="btn btn-primary">Edit</button>
                <!--                </router-link>-->
                <button class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</template>

<script setup>
// import '../js/bootstrap'
import {onMounted, ref} from "vue";

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
