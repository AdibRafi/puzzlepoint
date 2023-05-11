<template>
   <div :class="'card w-full p-6 bg-base-100 shadow-xl ' + (topMargin || 'mt-6')">
      <div class="card-title inline-block">
         {{ title }}
         <div class="inline-block float-right">
            <button v-if="topRightButtonLabel"
               class="btn btn-primary px-6 btn-sm normal-case" @click="$emit('')">{{ topRightButtonLabel }}</button>
         </div>
      </div>
   </div>
</template>

<script setup>
defineProps({
   topMargin: String,
   title: String,
   topRightButtonLabel: String,
})


</script>
