<template>
<div class="card w-full shadow-xl my-4 bg-base-100 border-2 border-base-300">
    <div class="card-body">
        <h2 class="card-title">{{title}}</h2>
        <slot />
    </div>
</div>
</template>
<script setup>
defineProps({
    title: String,
})
</script>
