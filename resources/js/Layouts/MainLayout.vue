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
                    <li>
                        <Link :href="route('test.index')">Test</Link>
                    </li>
                    <li>
                        <Link :href="route('student.expert',{topic_id: 1})">Student Expert</Link>
                    </li>
                    <li>
                        <Link :href="route('student.jigsaw',{topic_id: 1})">Student Jigsaw</Link>
                    </li>
                    <!--                    <li tabindex="0">-->
                    <!--                        <a>-->
                    <!--                            SESSION-->
                    <!--                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
                    <!--                                 viewBox="0 0 24 24">-->
                    <!--                                <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>-->
                    <!--                            </svg>-->
                    <!--                        </a>-->
                    <!--                        <ul class="p-2 bg-base-100">-->
                    <!--                            <li>-->
                    <!--&lt;!&ndash;                                <Link :href="route('lect.session.expert')">Lecturer</Link>&ndash;&gt;-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </li>-->
                </ul>
            </div>
        </div>
        <div class="navbar-center">
            <Link :href="route('classroom.index')" class="btn btn-ghost normal-case text-xl">
                PuzzlePoint
            </Link>
            <p>{{ $page.props.user.name }}</p>
        </div>
        <div class="navbar-end">
            <Link class="btn btn-ghost btn-circle" :href="route('profile.edit')">
                <font-awesome-icon class="scale-125" icon="fa-solid fa-user"/>
            </Link>
            <Link class="btn btn-ghost btn-circle" :href="route('logout')" method="post">
                <font-awesome-icon class="scale-125" icon="fa-solid fa-arrow-right-from-bracket"/>
            </Link>
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
                        <font-awesome-icon icon="fa-regular fa-circle-check"/>
                        <span>{{ $page.props.message.alertMessage }}</span>
                    </div>
                </div>
                <div class="mb-14">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
    <!--    todo: perlu ke? for what?-->
    <footer class="footer items-center p-4 bg-neutral text-neutral-content">
        <div class="items-center grid-flow-col">
            <svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                 clip-rule="evenodd" class="fill-current">
                <path
                    d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path>
            </svg>
            <p>Copyright © 2023 - All right reserved</p>
        </div>
        <div class="grid-flow-col gap-4 md:place-self-center md:justify-self-end">
            <a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path
                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                </svg>
            </a>
            <a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path
                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
                </svg>
            </a>
            <a>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                    <path
                        d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
                </svg>
            </a>
        </div>
    </footer>
</template>

<script setup>
import {Link, router} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

</script>

<style scoped>
</style>
