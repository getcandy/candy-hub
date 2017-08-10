<template>
    <div>
        <select :class="{'input-group-addon' : addon, 'selectpicker' : true, 'form-control' : true}" :required="required" @change="updateValue($event.target.value)" :value="value">
            <option v-for="option in options" :value="option.value ? option.value : option" :data-content="option.content ? option.content : ''">
                {{ option.label ? option.label : option }}
            </option>
        </select>
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
            },
            addon: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            this.refresh();
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value);
            },
            refresh() {
                const $selectpicker = $(this.$el).find('select');
                $selectpicker.selectpicker('render');
            },
        }
    }
</script>