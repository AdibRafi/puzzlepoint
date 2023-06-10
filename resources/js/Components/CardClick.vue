<template>
    <div class="indicator">
        <div class="card w-96 bg-base-100 shadow-xl my-4 hover:bg-base-200 cursor-pointer">
            <span v-if="is_new" class="indicator-item badge badge-success">New</span>
            <div class="card-body">
                <h2 class="card-title">{{ props.title }}</h2>
                <slot/>
                <div class="card-actions justify-end">
                    <slot name="actions"/>
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
