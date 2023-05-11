<template>
   <div class="drawer-side">
      <label for="left-sidebar-drawer"
             class="drawer-overlay"/>
      <ul class="menu pt-2 w-80 bg-base-100 text-base-content">
         <button class="btn btn-ghost bg-base-300  btn-circle z-50 top-0 right-0 mt-4 mr-2 absolute lg:hidden"
                 @click="close">
            <font-awesome-icon icon="fa-solid fa-xmark"
                               class="h-5 inline-block w-5"/>
         </button>
         <li class="mb-2 font-semibold text-xl">
            <Link :href="route('classroom.index')">
               <img class="mask mask-squircle w-10" src="/PuzzlePointLogo.png" alt="Logo"/>
               Classroom
            </Link>
         </li>
         <li>
            <Link :href="route('display.group',{topic_id:1})"
                  :class="route().current('display.group') ? activeRoute : inactiveRoute">
               Display Group
               <span v-if="route().current('display.group')" :class="activeRouteSpan"
                     aria-hidden="true"/>
            </Link>
         </li>
         <li>
            <Link :href="route('test.index')"
                  :class="route().current('test.index') ? activeRoute : inactiveRoute">
               Test
               <span v-if="route().current('test.index')"
                     :class="activeRouteSpan"
                     aria-hidden="true"/>
            </Link>
         </li>
         <li>
            <Link :href="route('profile.edit')"
                  :class="route().current('profile.edit') ? activeRoute : inactiveRoute">
               Profile
               <span v-if="route().current('profile.edit')"
                     :class="activeRouteSpan"
                     aria-hidden="true"/>
            </Link>
         </li>
         <li v-for="classroomData in usePage().props.auth.classrooms" :key="classroomData">
            <Link :href="route('classroom.show',classroomData.id)"
            :class="route().current('classroom.show',classroomData.id) ? activeRoute : inactiveRoute">
               {{ classroomData.name }}
               <span v-if="route().current('classroom.show',classroomData.id)"
                     :class="activeRouteSpan"
                     aria-hidden="true" />
            </Link>
         </li>
      </ul>
   </div>
</template>
<script setup>

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Link, usePage} from "@inertiajs/vue3";
import SidebarSubmenu from "@/Layouts/SidebarSubmenu.vue";

const close = (e) => {
   document.getElementById('left-sidebar-drawer').click()
}

const inactiveRoute = 'font-normal ';
const activeRoute = 'font-semibold bg-base-200 '
const activeRouteSpan = 'absolute inset-y-0 left-0 w-1 rounded-tr-md rounded-br-md bg-primary';


</script>
