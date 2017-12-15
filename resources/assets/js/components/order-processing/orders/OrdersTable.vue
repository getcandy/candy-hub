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
                apiRequest.send('get', '/orders', this.params)
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
                var type = 'success'
                var text = 'Complete';
                switch (order.status) {
                    case 'open':
                        type = 'info';
                        text = 'Open';
                        break;
                    case 'refunded':
                        type = 'warning';
                        text = 'Refunded';
                        break;
                    case 'void':
                        type = 'danger';
                        text = 'Void';
                        break;
                    case 'expired':
                        type = 'default';
                        text = 'Expired';
                        break;
                    default:
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
                            <th width="10%">Status</th>
                            <th>Customer</th>
                            <th width="19%">Total</th>
                            <th width="19%">Tax</th>
                            <th width="19%">Shipping</th>
                            <th width="19%">Currency</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="order in orders">
                            <td @click="loadOrder(order.id)"><span class="label" :class="status(order).class">{{ status(order).text }}</span></td>
                            <td @click="loadOrder(order.id)">{{ order.billing.firstname }} {{ order.billing.lastname }}</td>
                            <td @click="loadOrder(order.id)">
                                <span v-html="localisedPrice(order.total, order.currency)"></span>
                            </td>
                            <td @click="loadOrder(order.id)">
                                <span v-html="localisedPrice(order.vat, order.currency)"></span>
                            </td>
                            <td @click="loadOrder(order.id)">
                                <span v-html="localisedPrice(order.shipping_total, order.currency)"></span>
                            </td>
                            <td @click="loadOrder(order.id)">{{ order.currency }}</td>
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