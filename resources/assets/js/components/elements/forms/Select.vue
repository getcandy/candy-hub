<template>

        <select class="selectpicker" :class="{'input-group-addon' : addon}" :required="required" @input="updateValue($event.target.value)" :value="value">
            <option value disabled>Please select</option>
            <option v-for="option in options" :value="option.value ? option.value : option">
                {{ option.label ? option.label : option }}
            </option>
        </select>

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
            },
            addon: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            $('.selectpicker').selectpicker('render');
            $(this.$el).on('changed.bs.select', (event, clickedIndex, newValue, oldValue) => {
                this.$emit('input', $(this.$el).find("option:selected").val());
            });
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        }
    }
</script>