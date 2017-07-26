<template>

        <select id="afddddd" :class="{'input-group-addon' : addon, 'selectpicker' : true, 'form-control' : true}" :required="required" @change="updateValue($event.target.value)" :value="value">
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
            $(this.$el).selectpicker('render');
            /*
            $(this.$el).on('changed.bs.select', (event, clickedIndex, newValue, oldValue) => {
                this.$emit('input', event.val());
            });*/
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value);
            }
        }
    }
</script>