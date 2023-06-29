<template>
   <div class="drawer-side">
      <label for="left-sidebar-drawer"
             class="drawer-overlay"/>
      <ul class="menu pt-16 lg:pt-2 w-72 h-full bg-base-100 text-base-content">
         <li class="mb-2 font-semibold text-xl">
            <Link :href="route('classroom.index')">
               <img class="mask mask-squircle w-10" src="/PuzzlePointLogo.png" alt="Logo"/>
               PuzzlePoint
            </Link>
         </li>
         <li>
            <Link :href="route('profile.edit')"
                  :class="route().current('profile.edit') ? activeRoute : inactiveRoute">
               <font-awesome-icon icon="fa-regular fa-address-card" size="lg"/>
               Profile
               <span v-if="route().current('profile.edit')"
                     :class="activeRouteSpan"
                     aria-hidden="true"/>
            </Link>
         </li>
         <li v-for="classroomData in usePage().props.auth.classrooms" :key="classroomData">
            <Link :href="route('classroom.show',classroomData.id)"
                  :class="route().current('classroom.show',classroomData.id) ? activeRoute : inactiveRoute">
               <font-awesome-icon icon="fa-solid fa-arrow-up-right-from-square" />
               {{ classroomData.name }}
               <span v-if="route().current('classroom.show',classroomData.id)"
                     :class="activeRouteSpan"
                     aria-hidden="true"/>
            </Link>
         </li>
<!--         <div class="divider"/>-->
<!--         <li v-if="route().current('classroom.*') || route().current('topic.*') || route().current('module.*') || route().current('option.*') ||-->
<!--         route().current('assessment.*')">-->
<!--            <Link :href="route('topic.create',-->
<!--               {classroom_id:route().params.classroom})"-->
<!--                  :class="route().current('topic.create') ? activeRoute : inactiveRoute">-->
<!--               Add Topic-->
<!--               <span v-if="route().current('topic.create')"-->
<!--                     :class="activeRouteSpan"-->
<!--                     aria-hidden="true"/>-->
<!--            </Link>-->
<!--         </li>-->
<!--         <li v-else>-->
<!--            <p>Please choose a classroom</p>-->
<!--         </li>-->
         <div class="divider"/>
         <li>
            <div class="flex-col">
                <div class="w-full" @click="setIsExpanded">
                    DEVELOPER

               </div>
               <div :class="'w-full ' + (isExpanded ? '' : 'hidden')">
                  <ul class="menu menu-compact">
                     <li>
                        <Link :href="route('test.index')"
                              :class="route().current('text.index') ? activeRoute : inactiveRoute">
                           test
                           <span v-if="route().current('test.index')"
                                 :class="activeSubRouteSpan"
                                 aria-hidden="true"/>
                        </Link>
                     </li>
                     <li>
                        <Link :href="route('display.group',{topic_id:1})"
                              :class="route().current('display.group',{topic_id:1}) ? activeRoute : inactiveRoute">
                           Display Group
                           <span v-if="route().current('display.group')"
                                 :class="activeSubRouteSpan"
                                 aria-hidden="true"/>
                        </Link>
                     </li>
                  </ul>
               </div>
            </div>
         </li>
      </ul>
   </div>
</template>
<script setup>

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Link, router, usePage} from "@inertiajs/vue3";
import SidebarSubmenu from "@/Layouts/SidebarSubmenu.vue";
import {onMounted, ref} from "vue";

const inactiveRoute = 'font-normal ';
const activeRoute = 'font-semibold '
const activeRouteSpan = 'absolute inset-y-0 left-0 w-1 rounded-tr-md rounded-br-md bg-primary';
const activeSubRouteSpan = 'absolute mt-1 mb-1 inset-y-0 left-0 w-1 rounded-tr-md rounded-br-md bg-primary';

const isExpanded = ref(false);
const setIsExpanded = () => {
   isExpanded.value = !isExpanded.value
}
const close = (e) => {
   document.getElementById('left-sidebar-drawer').click()
}

onMounted(() => {
   if (route().current('display.group') || route().current('test.index')) {
      isExpanded.value = true
   }
})

</script>
