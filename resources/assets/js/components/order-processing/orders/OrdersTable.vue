<script>
    import Orders from '../../../mixins/OrderMixin';
    export default {
        mixins: [Orders],
        data() {
            return {
                loaded: false,
                orders: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                filter: null,
                currencies: [],
                keywords: null,
                params: {
                    per_page: 50,
                    current_page: 1,
                    includes: 'user,shipping,lines'
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
        watch: {
            filter() {
                this.loadOrders();
                this.params.current_page = 1;
            }
        },
        methods: {
            loadOrders() {
                this.loaded = false;

                if (this.filter) {
                    this.params.status = this.filter;
                }

                if (this.keywords) {
                    this.params.keywords = this.keywords;
                }

                apiRequest.send('get', '/orders', [], this.params)
                    .then(response => {
                        this.orders = response.data;
                        this.pagination = response.meta.pagination;
                        this.getStatuses();
                        apiRequest.send('GET', 'currencies').then(response => {
                            this.currencies = response.data;
                            this.loaded = true;
                        });
                    });
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.params['keywords'] = this.keywords;
                    this.loadOrders();
                }, 500
            ),
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadOrders();
            },
            getShippingZone(order) {
                let shipping = _.find(order.lines.data, line => {
                    return line.is_shipping;
                });
                return shipping ? shipping.variant : null;
            },
            loadOrder: function (id) {
                location.href = route('hub.orders.edit', id);
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
        <ul class="nav nav-tabs order-status-tabs" role="tablist">
            <li role="presentation" :class="{'active' : !filter}">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'processed'">
                    All Orders
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'payment-processing'}" class="processing">
                <a href="#payment-processing" aria-controls="payment-processing" role="tab" data-toggle="tab" @click="filter = 'payment-processing'">
                    Payment Processing
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'payment-received'}" class="live">
                <a href="#payment-received" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'payment-received'">
                    Payment received
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'on-account'}" class="live">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'on-account'">
                    On Account
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'in-progress'}" class="pending">
                <a href="#in-progress" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'in-progress'">
                    In Progress
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'dispatched'}" class="default">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'dispatched'">
                    Dispatched
                </a>
            </li>

            <li role="presentation" :class="{'active' : filter == 'failed'}" class="danger">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = 'failed'">
                    Failed
                </a>
            </li>
            <li role="presentation" :class="{'active' : filter == 'awaiting-payment'}" class="waiting">
                <a href="#awaiting-payment" aria-controls="awaiting-payment" role="tab" data-toggle="tab" @click="filter = 'awaiting-payment'">
                    Awaiting Payment
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
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Order Id</th>
                            <th>Customer Name</th>
                            <th>Customer Type</th>
                            <th>Sub Total</th>
                            <th>Discount Total</th>
                            <th>Shipping Total</th>
                            <th>Tax Total</th>
                            <th>Total</th>
                            <th>Currency</th>
                            <th v-if="filter != 'awaiting-payment'">Shipping Zone</th>
                            <th v-if="filter != 'awaiting-payment'">Date Placed</th>
                            <th v-else>Date Created</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="order in orders" @click="loadOrder(order.id)">
                            <td><span  class="order-status" :class="order.status">{{ status(order.status) }}</span></td>
                            <td>
                                {{ order.reference }}
                            </td>
                            <td>
                                {{ order.customer_name }}
                            </td>
                            <td>
                                <span v-if="order.user">Account</span>
                                <span v-else>Guest</span>
                            </td>
                            <td><span v-html="localisedPrice(order.sub_total, order.currency)"></span></td>
                            <td><span v-html="localisedPrice(order.discount_total, order.currency)"></span></td>
                            <td><span v-html="localisedPrice(order.delivery_total, order.currency)" data-toggle="tooltip" data-placement="bottom" :title="order.shipping_method"></span></td>
                            <td><span v-html="localisedPrice(order.tax_total, order.currency)"></span></td>
                            <td><span v-html="localisedPrice(order.order_total, order.currency)"></span></td>
                            <td>{{ order.currency }}</td>
                            <td v-if="filter != 'awaiting-payment'">
                                <template v-if="getShippingZone(order)">
                                    {{ getShippingZone(order) }}
                                </template>
                                <template v-else>
                                    -
                                </template>
                            </td>
                            <td v-if="filter != 'awaiting-payment'">
                                {{ order.placed_at.date|formatDate('Do MMM YYYY, H:mm:ss') }}
                            </td>
                            <td v-else>
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