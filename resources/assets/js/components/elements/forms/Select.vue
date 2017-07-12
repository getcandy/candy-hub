<template>
    <div>
        <select class="form-control" :class="{'input-group-addon' : addon}" :required="required" @input="updateValue($event.target.value)" :value="value">
            <option value disabled>Please select</option>
            <option v-for="option in options" :value="option.value ? option.value : option">
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
            // let vm = this;
            // $(this.$el).on('changed.bs.select', (event, clickedIndex, newValue, oldValue) => {
            //     console.log(event.target.value);
            // });
        },
        methods: {
            updateValue(value) {
                this.$emit('input', value)
            }
        }
    }
</script>