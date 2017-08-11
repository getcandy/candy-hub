<template>
    <div>
        <div class="time-picker input-group input-group-full">
            <span class="input-group-addon">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
            </span>
            <select class="form-control select-picker" @load="" @change="updateValue($event.target.value)" :value="value" :required="required">

                <option disabled selected value>Select a time</option>
                <option v-for="option in options" :value="option">
                    {{ option }}
                </option>

            </select>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Array,
                default: ['09:00am','09:30']
            },
            value: {
                type: String
            },
            required: {
                type: Boolean
            }
        },
        mounted() {
            const $selectpicker = $(this.$el).find('.select-picker');
            $selectpicker.selectpicker('render');

            this.getTimes();
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value);
            }
        }
    }
</script>