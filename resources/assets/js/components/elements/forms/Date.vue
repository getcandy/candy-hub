<template>
    <div>
        <div class="input-group input-group-full date" data-provide="datepicker">
            <span class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
            <label class="sr-only" for="search">Date</label>
            <input type="text" class="form-control" :value="value" >
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
            $(this.$elem).datepicker();

            $(this.$elem).datepicker().on(changeDate, function(e){
                this.$emit('input', 'sadsd');
            });
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
            /*
            updateValue: function (value) {
                alert(value);
                this.$emit('input', value);
            }*/
        }
    }
</script>