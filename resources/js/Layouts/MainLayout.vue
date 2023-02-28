<template>
    <div class="navbar bg-base-100 bg-emerald-500">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost btn-circle">
                    <font-awesome-icon class="scale-125" icon="fa-solid fa-bars"/>
                </label>
                <ul tabindex="0"
                    class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <router-link :to="{name:'lectDashboard'}">Home</router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'lectCreateTopic'}">Topic</router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'lectCreateModule'}">Module</router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'lectCreateOption'}">Option</router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'lectCreateVerify'}">Verify</router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'lectCreateAssessment'}">Assessment</router-link>
                    </li>
                    <li tabindex="0">
                        <a>
                            SESSION
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24">
                                <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>
                            </svg>
                        </a>
                        <ul class="p-2 bg-base-100">
                            <li>
                                <router-link :to="{name:'lectExpertSession'}">Expert Session</router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar-center">
            <router-link class="btn btn-ghost normal-case text-xl" :to="{name:'lectDashboard'}">PuzzlePoint
            </router-link>
        </div>
        <div class="navbar-end">
            <Link class="btn btn-ghost btn-circle" :href="route('profile.edit')">
                <font-awesome-icon class="scale-125" icon="fa-solid fa-user"/>
            </Link>
            <button class="btn btn-ghost btn-circle">
                <font-awesome-icon class="scale-125" icon="fa-solid fa-arrow-right-from-bracket"/>
            </button>
        </div>
    </div>

    <!--todo: find a way untuk dpt heading-->
<!--    <header class="bg-white shadow" v-if="$slots.header">-->
<!--        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">-->
<!--            <slot name="header"/>-->
<!--        </div>-->
<!--    </header>-->

    <!-- Page Content -->
    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-6 bg-gray-100 justify-center rounded-xl min-h-screen">
            <div class="flex flex-col items-center p-6">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

</script>

<style scoped>
</style>
