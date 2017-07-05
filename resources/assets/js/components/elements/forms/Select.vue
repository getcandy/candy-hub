<template>
    <div>
        <select class="form-control">
            <option v-for="option in options" :value="option.value" :required="required">{{ option.label }}</option>
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
                type: String
            }
        },
        methods: {
            updateInputValue(value) {
                this.$emit('input', value)
            }
        }
    }
</script>