<template>
    <div>
        <textarea class="form-control"
                  rows="8"
                  :value="value"
                  @input="updateValue($event.target.value)"
                  :required="required"
                  :placeholder="placeholder"
                  :disabled="disabled">
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