<template>
    <div>
        <textarea class="form-control" @input="updateInputValue($event.target.value)">{{ value }}</textarea>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
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