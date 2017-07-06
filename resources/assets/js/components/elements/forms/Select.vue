<template>
    <div>
        <select class="form-control" :required="required" @input="updateValue($event.target.value)">
            <option v-for="option in options" :value="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Array
            },
            value: {
                type: String
            },
            required: {
                type: Boolean
            }
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        }
    }
</script>