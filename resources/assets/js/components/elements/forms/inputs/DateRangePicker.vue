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
                config: {
                    alwaysShowCalendars: true,
                    autoUpdateInput: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'Last Year': [moment().subtract(1, 'year'), moment()],
                        'Last 10 Years': [moment().subtract(10, 'year'), moment()],
                    },
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                }
            }
        },
        mounted() {

            if (this.from) {
                this.config.startDate = moment(this.from);
            }
            if (this.to) {
                this.config.endDate = moment(this.to);
            }

            const picker = $(this.$refs.daterange).daterangepicker(this.config);

            picker.on('apply.daterangepicker', this.update);

            // const picker = new DateRangePicker(this.$refs.daterange, {
            //     applyDaterangePicker: (ev, picker) => {
            //         console.log('hi!');
            //     }
            // });
        },
        methods: {
            update(event, picker) {
                $(this.$refs.daterange).val(
                    picker.startDate.format(this.config.locale.format) + ' - ' + picker.endDate.format(this.config.locale.format)
                );
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