<template>
    <div class="btn-holder">
        <button class="btn btn-xs btn-default" @click="getExport">Export Orders</button>
        <candy-modal title="Export Orders" v-show="show" @closed="reset">
                <div slot="body" class="text-left">
                    <div class="form-group">
                        <label>Choose a format</label>
                        <select class="form-control">
                            <option value="csv">CSV</option>
                            <option value="dpd">DPD Delivery</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Update status</label>
                        <select class="form-control" v-model="status">
                            <option value="">No Change</option>
                            <option value="csv" v-for="(status, handle) in statuses" :key="handle">{{ status.label }}</option>
                        </select>
                    </div>
                </div>
                <template slot="footer">
                    <button type="button" class="btn btn-primary" @click="getExport">
                        <template v-if="!exporting">
                            Get Export
                        </template>
                        <template v-else>
                            <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i> Exporting
                        </template>
                    </button>
                </template>
            </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            ids: {
                type: Array|Object,
                default() {
                    return [];
                }
            },
            statuses: {
                type: Array|Object,
                default() {
                    return [];
                }
            }
        },
        data() {
            return {
                show: false,
                exporting: false,
                format: 'csv',
                status: "",
            };
        },
        methods: {
            reset() {
                this.exporting = false;
                this.show = false;
            },
            getExport() {
                this.exporting = true;
                let url = route('hub.orders.export', {
                    format: this.format,
                });
                window.location = url + '?orders=' + this.ids.join(':');
            }
        }
    }
</script>

<style scoped>

</style>