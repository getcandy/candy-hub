<template>
    <div>

        <div class="radio" v-for="option in options" @input="updateValue($event.target.value)" :required="required">
            <label>
                <input type="radio" name="option" id="optionsRadios1" :value="option.value">
                {{ option.label }}
            </label>
        </div>

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