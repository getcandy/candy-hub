<template>
    <div>
        <div class="row">
            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Sales Today</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div>
                            <template  v-if="salesMetrics">
                                <metric-badge :top="salesMetrics.today" :bottom="salesMetrics.yesterday" :money="true" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                            <!-- <section style="margin-top:10px;font-size:.75em">
                                @if($sales_this_week - $sales_last_week >= 0)
                                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 0) }}</span>
                                @else
                                    <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 0) }}</span>
                                @endif
                            </section> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Sales this week</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div>
                            <template  v-if="salesMetrics">
                                <metric-badge :top="salesMetrics.current_week" :bottom="salesMetrics.previous_week" :money="true" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                            <!-- <section style="margin-top:10px;font-size:.75em">
                                @if($sales_this_week - $sales_last_week >= 0)
                                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 0) }}</span>
                                @else
                                    <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>&pound;{{ number_format($sales_this_week - $sales_last_week, 0) }}</span>
                                @endif
                            </section> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Sales this month</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div class="">
                            <template  v-if="salesMetrics">
                                <metric-badge :top="salesMetrics.current_month" :bottom="salesMetrics.previous_month" :money="true" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                            <!-- &pound;{{ number_format($sales_this_month, 0) }} <br>
                            <section style="margin-top:10px;font-size:.75em">
                                @if($sales_this_month - $sales_last_month >= 0)
                                    <span class="text-success"><sup><fa icon="caret-up"></fa></sup>&pound;{{ number_format($sales_this_month - $sales_last_month, 0) }}</span>
                                @else
                                    <span class="text-danger"><sup><fa icon="caret-down"></fa></sup>&pound;{{ number_format($sales_this_month - $sales_last_month, 0) }}</span>
                                @endif
                            </section> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Orders today</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div class=" ">
                            <template  v-if="orderMetrics">
                                <metric-badge :top="orderMetrics.today" :bottom="orderMetrics.yesterday" :money="false" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Orders this week</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div class="">
                            <template  v-if="orderMetrics">
                                <metric-badge :top="orderMetrics.current_week" :bottom="orderMetrics.previous_week" :money="false" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-2">
                <div class="panel">
                    <header class="panel-heading">
                        <h3 class="metric-title">Orders this month</h3>
                    </header>
                    <div class="panel-body dashboard-figure">
                        <div class=" ">
                            <template  v-if="orderMetrics">
                                <metric-badge :top="orderMetrics.current_month" :bottom="orderMetrics.previous_month" :money="false" />
                            </template>
                            <template v-else>
                                <i class="fa fa-sync fa-spin"></i>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MetricBadge from './MetricBadge';

    export default {
        components: {
            MetricBadge,
        },
        data() {
            return {
                orderMetrics: null,
                salesMetrics: null,
                loadingSales: true,
                loadingOrders: true,
            };
        },
        mounted() {
            apiRequest.send('GET', '/reports/metrics/orders').then(response => {
                this.orderMetrics = response;
            });
            apiRequest.send('GET', '/reports/metrics/sales').then(response => {
                this.salesMetrics = response;
            });
        }
    }
</script>

<style scoped>

</style>