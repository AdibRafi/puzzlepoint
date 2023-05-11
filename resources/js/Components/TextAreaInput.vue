<template>
   <div :class="'form-control w-full ' + containerStyle">
      <label class="label">
         <span :class="'label-text text-base-content ' + labelStyle">
            {{ labelTitle }}
         </span>
      </label>
      <textarea class="textarea textarea-bordered w-full"
                :value="modelValue"
                @input="$emit('update:modelValue',$event.target.value)"
                :placeholder="placeholder||''"/>
   </div>

</template>

<script setup>
defineProps({
   containerStyle: String,
   labelStyle: String,
   labelTitle: String,
   placeholder: String,
   modelValue: String,

})
</script>
