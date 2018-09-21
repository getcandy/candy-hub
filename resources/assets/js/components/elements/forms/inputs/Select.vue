<template>
    <div class="candy-select">
        <select ref="select" :class="{'input-group-addon' : addon, 'selectpicker' : true, 'form-control' : true}" :required="required" @change="updateValue($event.target.value)" :value="value">
            <option value readonly>{{ nullLabel }}</option>
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
                type: Array|Object
            },
            nullLabel: {
                default: 'Please select',
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
        created() {
            CandyEvent.$on('selectUpdated', event => {
                this.refresh();
            });
        },
        watch: {
            value() {
                this.$nextTick(function() {
                    $(this.$refs.select).selectpicker('refresh');
                });
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
                // const $selectpicker = .find('select');
                $(this.$refs.select).selectpicker('render');
            },
        }
    }
</script>