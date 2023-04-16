<template>
    <div v-for="groupData in props.groupUserAttendModal" class="m-4">
        <div class="card w-96 bg-base-100 shadow-xl ">
            <div class="card-body">
                <h2 class="card-title">{{ groupData.name }}</h2>
                <p>This is for {{groupData.type}}</p>
                <div v-for="userData in groupData.users">
                    <p v-if="userData.attendances[0].attend_status === 'absent'" class="text-red-500">{{userData.name}}</p>
                    <p v-else>{{userData.name}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    groupUserAttendModal: Object,
})
</script>

<style scoped>

</style>
