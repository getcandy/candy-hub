<template>
    <div>
        <div class="input-group input-group-full date">
            <span class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
            <label class="sr-only" for="search">Date</label>
            <input type="text" class="form-control" :value="value" @input="updateValue($event.target.value)">
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                date: new Date(),
                config: {
                    enableTime:true
                }
            }
        },
        mounted() {
            $(this.$elem).datetimepicker();
        },
        props: {
            value: {
                type: String
            },
            id: {
                type: String,
                default: null
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