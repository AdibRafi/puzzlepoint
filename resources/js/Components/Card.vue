<template>
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">{{ props.title }}</h2>
            <slot/>
            <div class="card-actions justify-end">
                <slot name="actions"/>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    title: String,
})
</script>

<style scoped>

</style>
