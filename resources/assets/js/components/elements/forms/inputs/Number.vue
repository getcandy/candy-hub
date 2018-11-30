<template>
    <div>
        <input type="number"
               :value="value"
               @input="updateValue($event.target.value)"
               class="form-control"
               :required="required"
               :placeholder="placeholder"
               :disabled="disabled">
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String
            },
            translate: {
                type: String,
                default: null
            },
            required: {
                type: Boolean
            },
            placeholder: {
                type: String
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