<template>
   <div :class="'card w-full p-6 bg-base-100 shadow-xl ' + (topMargin || 'mt-6')">
      <div class="inline-block">
         <h2 class="card-title">{{ title }}</h2>
         <button v-if="topRightButtonLabel"
                 :class="'btn px-6 btn-sm normal-case inline-block float-right ' + (topRightButtonLabel === 'Destroy' ? 'btn-warning':'btn-primary')"
                 @click="$emit('buttonFunction')">{{ topRightButtonLabel }}
         </button>
      </div>
      <div class="divider mt-2"/>
      <div class="h-full w-full pb-6 bg-base-100">
         <slot/>
      </div>
   </div>
</template>

<script setup>
defineProps({
   topMargin: String,
   title: String,
   topRightButtonLabel: String,
})

defineEmits(['buttonFunction'])

</script>
