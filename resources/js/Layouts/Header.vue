<template>
    <div class="navbar flex justify-between bg-base-100 z-10 shadow-md">
        <div>
            <label v-if="$page.props.auth.user.is_wizard_complete === 1"
                for="left-sidebar-drawer"
                   class="btn btn-primary drawer-button lg:hidden">
                <font-awesome-icon icon="fa-solid fa-bars"
                                   class="h-5 inline-block w-5"/>
            </label>
            <h1 class="text-2xl font-semibold ml-2">{{ pageTitle }}</h1>
        </div>
        <div class="order-last">
            <select class="select mr-4" data-choose-theme>
                <option disabled selected>Theme</option>
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="corporate">Corporate</option>
                <option value="business">Business</option>
                <option value="emerald">Emerald</option>
            </select>
            <div class="dropdown dropdown-end ml-4">
                <label tabIndex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://placeimg.com/80/80/people" alt="profile"/>
                    </div>
                </label>
                <ul tabIndex="0"
                    class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                    <li class="justify-between">
                        <Link :href="route('profile.edit')">
                            Profile Settings
                        </Link>
                    </li>
                    <!--               <li class=''><Link to={'/app/settings-billing'}>Bill History</Link></li>-->
                    <div class="divider mt-0 mb-0"/>
                    <li>
                        <Link :href="route('logout')" method="post">
                            Logout
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps({
    pageTitle: String,
})
</script>
