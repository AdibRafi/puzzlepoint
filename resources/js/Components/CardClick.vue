<template>
    <div class="indicator">
        <div class="shadow bg-base-100 border-2 w-80">
            <div class="stat hover:bg-base-200 cursor-pointer p-4">
                <div class="">
                    <span v-if="is_new" class="indicator-item badge badge-success">New</span>
                    <h2 class="card-title">{{ props.title }}</h2>
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    title: String,
    is_new: Boolean,
})
</script>

<style scoped>

</style>
