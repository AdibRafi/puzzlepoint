<template>
   <div class="drawer-side">
      <label for="left-sidebar-drawer"
             class="drawer-overlay" />
      <ul class="menu pt-2 w-80 bg-base-100 text-base-content">
         <button class="btn btn-ghost bg-base-300  btn-circle z-50 top-0 right-0 mt-4 mr-2 absolute lg:hidden"
         @click="close">
            <font-awesome-icon icon="fa-solid fa-xmark"
            class="h-5 inline-block w-5"/>
         </button>
         <li class="mb-2 font-semibold text-xl">
            <Link :href="route('classroom.index')">
               <img class="mask mask-squircle w-10" src="/logo192.png" />
               Classroom
            </Link>
         </li>
      </ul>
   </div>
</template>
<script setup>

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Link} from "@inertiajs/vue3";
const close = (e) =>{
   document.getElementById('left-sidebar-drawer').click()
}
</script>
