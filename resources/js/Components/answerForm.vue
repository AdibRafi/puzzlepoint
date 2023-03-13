<template>
    <form @submit.prevent="form.route('answer.store')">
        <div v-if="radioAnsType ==='radio'">
            <div v-for="answerItems in radioAnsNumber">
                <label class="label cursor-pointer">
                    <input type="radio" name="radioAns" class="mr-2 radio"/>
                    <input type="text" placeholder="Type answer here"
                           class="input input-bordered input-sm w-full max-w-xs"
                           v-model="form.name[answerItems]"/>
                </label>
            </div>
            <div class="my-2">
                <span @click="radioAnsNumber--"
                      class="btn btn-circle float-left inline scale-90">
                    <font-awesome-icon icon="fa-solid fa-minus" class="scale-150 p-4"/>
                </span>
                <span @click="radioAnsNumber++"
                      class="btn btn-circle float-left inline scale-90">
                    <font-awesome-icon icon="fa-solid fa-plus" class="scale-150 p-4"/>
                </span>
            </div>
        </div>
    </form>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const radioAnsNumber = ref(1);

const props = defineProps({
    radioAnsType: String,
})

const form = useForm({
    name: {},
    right_answer: {}
})
</script>

<style scoped>

</style>
