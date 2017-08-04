<template>
    <div>
        <input type="text"
               :value="value"
               @input="updateValue($event.target.value)"
               class="form-control"
               :required="required"
               :disabled="disabled">
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String
            },
            required: {
                type: Boolean
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            }
        }
    }
</script>