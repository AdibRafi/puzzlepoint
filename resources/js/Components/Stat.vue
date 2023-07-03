<template>
    <div class="shadow bg-base-100 border-2 border-base-300">
        <div class="stat">
            <div v-if="figure"
                 class="stat-figure text-secondary">
                <font-awesome-icon :icon="figure" size="xl"/>
            </div>
            <div v-if="title" class="stat-title">{{ title }}</div>
            <div v-if="value" class="stat-value">{{ value }}</div>
            <slot/>
            <div v-if="desc" class="stat-desc">{{ desc }}</div>
        </div>
    </div>
</template>

<script setup>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps({
    title: String,
    value: String,
    desc: String,
    figure: String,
})
</script>
