<template>
    <div>

        <div class="radio" v-for="option in options" @change="updateValue($event.target.value)">
            <label>
                <input type="radio" name="option" id="optionsRadios1" :value="(option.label) ? option.label : option" :required="required">
                {{ (option.label) ? option.label : option }}
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