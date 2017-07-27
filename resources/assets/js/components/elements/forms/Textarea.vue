<template>
    <div>
        <textarea class="form-control" rows="8" :value="value" @input="updateValue($event.target.value)" :required="required">{{ value }}</textarea>
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
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            }
        }
    }
</script>