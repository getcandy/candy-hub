<script>
    export default {
        data() {
            return {
                loaded: false,
                orders: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                currencies: [],
                params: {
                    per_page: 50,
                    current_page: 1,
                    includes: 'user'
                },
                pagination: {}
            }
        },
        watch: {
            selected: function(val) {
                this.checkedCount = val.length;
                this.selectAll = (val.length === this.orders.length);
            },
            selectAll: function(val) {
                let selected = [];

                if (val) {
                    this.orders.forEach(function (order) {
                        selected.push(order.id);
                    });
                }
                this.selected = selected;
            }
        },
        mounted() {
            this.loadOrders();
        },
        methods: {
            loadOrders() {
                apiRequest.send('get', '/orders', [], this.params)
                    .then(response => {
                        this.orders = response.data;
                        this.pagination = response.meta.pagination;
                        apiRequest.send('GET', 'currencies').then(response => {
                            this.currencies = response.data;
                            this.loaded = true;
                        });
                    });
            },
            status(order) {
                var type = 'default'
                var text = 'Unknown';
                switch (order.status) {
                    case 'payment-received':
                        type = 'success';
                        text = 'Payment Received';
                        break;
                    case 'refunded':
                        type = 'warning';
                        text = 'Refunded';
                        break;
                    case 'returned':
                        type = 'warning';
                        text = 'Returned';
                        break;
                    case 'voided':
                        type = 'danger';
                        text = 'Void';
                        break;
                    case 'failed':
                        type = 'danger';
                        text = 'Failed';
                        break;
                    case 'dispatched':
                        type = 'info';
                        text = 'Dispatched';
                        break;
                    case 'expired':
                        type = 'default';
                        text = 'Expired';
                        break;
                    default:
                        type = 'warning';
                        text = 'Awaiting payment';
                        break;
                }
                return {
                    class: 'label-' + type,
                    text: text
                };
            },
            statusLabelText(order) {

            },
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadOrders();
            },
            loadOrder: function (id) {
                location.href = '/order-processing/orders/' + id;
            },
            localisedPrice(amount, currency) {
                var currency = _.find(this.currencies, item => {
                    return item.code == currency;
                });
                return currency.format.replace('{price}', amount.money(2, currency.thousand_point, currency.decimal_point));
            }
        }
    }
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab">
                    All Orders
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-collections">

                <!-- Search Form -->
                <form>
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-8">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Order Id</th>
                            <th>Invoice Id</th>
                            <th>Customer Name</th>
                            <th>Customer Type</th>
                            <th>Total</th>
                            <th>Shipping</th>
                            <th>Currency</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="order in orders" @click="loadOrder(order.id)">
                            <td><span class="label" :class="status(order).class">{{ status(order).text }}</span></td>
                            <td>
                                {{ order.reference }}
                            </td>
                            <td>
                                <span v-if="order.invoice_reference">{{ order.invoice_reference }}</span>
                                <span v-else class="text-muted"> - </span>
                            </td>
                            <td>
                                {{ order.customer_name }}
                            </td>
                            <td>
                                <span v-if="order.user">Account</span>
                                <span v-else>Guest</span>
                            </td>
                            <td>
                                <span v-html="localisedPrice(order.total, order.currency)"></span>
                            </td>
                            <td>
                                <span v-html="localisedPrice(order.shipping_total, order.currency)"></span>
                            </td>
                            <td>{{ order.currency }}</td>
                            <td>
                                {{ order.created_at.date|formatDate('Do MMM YYYY, H:mm:ss') }}
                            </td>
                        </tr>


                    </tbody>
                    <tfoot class="text-center" v-else>
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center">
                    <candy-table-paginate :pagination="pagination" @change="changePage"></candy-table-paginate>
                </div>
            </div>

        </div>
    </div>
</template>