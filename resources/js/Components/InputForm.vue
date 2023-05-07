<template>
    <div class="form-control w-full max-w-xs">
        <label :for="labelFor" class="label">
            <span class="label-text block font-medium text-gray-700">{{ labelName }}</span>
        </label>
        <input :type="inputType" :id="inputId" :placeholder="inputPlaceholder"
               class="input input-primary input-bordered input-sm w-full max-w-xs"
               :value="modelValue" @input='$emit("update:modelValue",$event.target.value)'/>
    </div>
</template>

<script setup>
import {ref, defineProps} from 'vue'

const props = defineProps({
    labelName: String,
    labelFor: String,
    inputType: String,
    inputId: String,
    inputPlaceholder: String,
    modelValue: String,
});
</script>

<style scoped>

</style>
