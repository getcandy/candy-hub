<template>
    <div>
        <input type="text" :value="value" @input="updateValue($event.target.value)" class="form-control" :required="required">
    </div>
</template>

<script>
    export default {

        props: {
            value: {
                type: String
            },
            required: {
                type: String
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            }
        }
    }
</script>