<template>
    <div>
        <textarea class="form-control" :value="foo" @input="updateInputValue($event.target.value)">{{ value }}</textarea>
    </div>
</template>

<script>
    export default {
        props: {
            foo: {
                type: String
            }
        },
        methods: {
            updateInputValue(foo) {
                this.$emit('input', foo)
            }
        }

    }
</script>