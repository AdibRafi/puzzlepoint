<template>
    <div>
        <h3>{{ question.text }}</h3>
        <div v-for="(option, index) in question.options" :key="index">
            <div v-if="question.multiple">
                <input type="checkbox" :id="'option-' + index" :value="option" v-model="selectedOptions">
                <label :for="'option-' + index">{{ option }}</label>
            </div>
            <div v-else>
                <input type="radio" :id="'option-' + index" :value="option" v-model="selectedOption">
                <label :for="'option-' + index">{{ option }}</label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        question: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            selectedOption: null,
            selectedOptions: [],
        };
    },
    methods: {
        answer() {
            this.$emit('answered', this.selectedOption || this.selectedOptions);
        },
    },
    watch: {
        selectedOption() {
            this.answer();
        },
        selectedOptions() {
            this.answer();
        },
    },
};
</script>
