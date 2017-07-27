<template>
    <div>
        <div class="datepicker input-group input-group-full date" data-provide="datepicker">
            <span class="input-group-addon">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
            <label class="sr-only" for="search">Date</label>
            <input type="text" class="form-control" :value="value">
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            const $datePicker = $(this.$el).find('.datepicker');
            $datePicker.datepicker();

            $datePicker.datepicker().on("changeDate", (e) =>
                this.$emit('input', e.format())
            );
        },
        props: {
            value: {
                type: String
            },
            required: {
                type: Boolean
            }
        }
    }
</script>