<template>
    <div v-for="groupData in props.groupUserModal" class="m-4">
        <div class="card w-96 bg-base-100 shadow-xl ">
            <div class="card-body">
                <h2 class="card-title">{{ groupData.name }}</h2>
                <p>This is for {{groupData.type}}</p>
                <div v-for="userData in groupData.users">
                    <p>{{userData.name}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    groupUserModal: Object,
})
</script>

<style scoped>

</style>
