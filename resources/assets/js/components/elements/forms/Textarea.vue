<template>
    <div>
        <textarea class="form-control"
                  rows="8"
                  :value="value"
                  @input="updateValue($event.target.value)"
                  :required="required" :disabled="disabled">
            {{ value }}
        </textarea>
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