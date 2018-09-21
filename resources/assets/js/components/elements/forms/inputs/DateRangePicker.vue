<template>
    <div>
        <div class="form-group has-feedback">
            <input ref="daterange" class="form-control">
            <i class="fa fa-calendar form-control-feedback"></i>
        </div>
    </div>
</template>

<script>
    import "bootstrap-daterangepicker/daterangepicker.css";
    import DateRangePicker from 'bootstrap-daterangepicker';

    export default {
        props : ['to', 'from'],
        data() {
            return {
                date: this.value,
            }
        },
        mounted() {
            const picker = $(this.$refs.daterange).daterangepicker({
                startDate: moment(this.from),
                endDate: moment(this.to),
                alwaysShowCalendars: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            picker.on('apply.daterangepicker', this.update);

            // const picker = new DateRangePicker(this.$refs.daterange, {
            //     applyDaterangePicker: (ev, picker) => {
            //         console.log('hi!');
            //     }
            // });
        },
        methods: {
            update(event, picker) {
                this.$emit('update', {
                    start: picker.startDate,
                    end: picker.endDate
                });
            }
        }
    }
</script>

<style scoped>

</style>