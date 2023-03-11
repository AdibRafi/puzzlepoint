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
<!--                        <Link :href="route('classroom.index')">Home</Link>-->
                    </li>
                    <li>
<!--                        <Link :href="route('lect.create.topic')">CreateTopic</Link>-->
                    </li>
                    <li>
<!--                        <Link :href="route('lect.create.module')">CreateModule</Link>-->
                    </li>
                    <li>
                        <Link :href="route('lect.create.option')">CreateOption</Link>
                    </li>
                    <li>
<!--                        <Link :href="route('lect.create.verify')">CreateVerify</Link>-->
                    </li>
                    <li>
<!--                        <Link :href="route('lect.assessment.create')">CreateAssessment</Link>-->
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
<!--                                <Link :href="route('lect.session.expert')">Lecturer</Link>-->
                            </li>
                            <li>
<!--                                <Link :href="route('stud.session.expert')">Student</Link>-->
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar-center">
            <Link :href="route('classroom.index')" class="btn btn-ghost normal-case text-xl">
                PuzzlePoint
            </Link>
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
                <div v-if="$page.props.message.alertMessage" class="alert alert-success shadow-lg">
                    <div>
                        <font-awesome-icon icon="fa-regular fa-circle-check" />
                        <span>{{ $page.props.message.alertMessage }}</span>
                    </div>
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

</script>

<style scoped>
</style>
