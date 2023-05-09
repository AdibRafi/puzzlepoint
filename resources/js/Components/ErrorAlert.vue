<template>
    <div class="alert alert-error w-full shadow-lg mb-4">
        <div>
            <font-awesome-icon icon="fa-solid fa-xmark" bounce/>
            <p>{{ errorText }}</p>
        </div>
    </div>
</template>
<script setup>
defineProps({
    errorText: Object,

})
</script>


<style scoped>

</style>
