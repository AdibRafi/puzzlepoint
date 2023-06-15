<template>
    <div :class="'form-control w-full '+ containerStyle">
        <label class="label">
            <span :class="'label-text text-base-content '+ labelStyle">{{ labelTitle }}</span>
        </label>
        <input :type="inputType || 'text'"
               :value="modelValue"
               :placeholder="placeholder || ''"
               @input='$emit("update:modelValue",$event.target.value)'
               class="input input-bordered w-full"
               :disabled="isDisabled">
    </div>
</template>

<script setup>
defineProps({
    labelTitle: String,
    labelStyle: String,
    inputType: String,
    containerStyle: String,
    placeholder: String,
    modelValue: String,
    isDisabled: Boolean,
})
</script>
