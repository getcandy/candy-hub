<script>
    import Orders from '../../../mixins/OrderMixin';
    import DateRangePicker from '../../elements/forms/inputs/DateRangePicker';
    import UrlHelper from '../../../classes/UrlHelpers';

    export default {
        mixins: [Orders],
        components: {
            DateRangePicker
        },
        data() {
            return {
                loaded: false,
                orders: [],
                selected: [],
                selectAll: false,
                zone: null,
                checkedCount: 0,
                bulkSaving:false,
                sendEmails: true,
                shippingZones: [],
                bulk: {
                    status: null,
                },
                filter: null,
                currencies: [],
                keywords: null,
                urlParams:  UrlHelper.params(),
                params: {
                    per_page: 50,
                    page: 1,
                    includes: 'user,shipping,lines',
                    from: null,
                    to: null
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
            },
            zone() {
                UrlHelper.setParam('zone', this.zone);
                this.loadOrders();
                this.params.page = 1;
            },
            filter() {
                UrlHelper.setParam('status', this.filter);
                this.loadOrders();
                this.params.page = 1;
            }
        },
        created() {
            this.params.to = this.urlParams.get('to');
            this.params.from = this.urlParams.get('from');
        },
        mounted() {

            this.filter = this.urlParams.get('status');
            this.zone = this.urlParams.get('zone');

            if (this.urlParams.get('keywords')) {
                this.keywords = this.urlParams.get('keywords');
            }

            apiRequest.send('GET', 'shipping/zones').then(response => {
                this.shippingZones = _.map(response.data, zone => {
                    return {
                        label: zone.name,
                        value: zone.name
                    };
                });
            });

            this.loadOrders();
            this.getStatuses();
        },
        methods: {
            clearDates() {
                this.params.from = null;
                this.params.to = null;
                UrlHelper.setParam('to');
                UrlHelper.setParam('from');
                this.loadOrders();
            },
            filterDate(event) {
                this.params.from = event.start.format('YYYY-MM-DD');
                this.params.to = event.end.format('YYYY-MM-DD');

                UrlHelper.setParam('to', this.params.to);
                UrlHelper.setParam('from', this.params.from);

                this.loadOrders();
            },
            bulkSave() {
                this.bulkSaving = true;

                if (this.bulk.status) {
                    apiRequest.send('POST', 'orders/bulk', {
                        orders: this.selected,
                        field: 'status',
                        value: this.bulk.status,
                        send_emails: this.sendEmails,
                    }).then(response => {
                        this.bulkSaving = false;
                        this.loadOrders();
                        this.selected = [];
                    }).catch(response => {
                        this.bulkSaving = false;
                    });
                } else {
                    this.bulkSaving = false;
                }
            },
            loadOrders() {
                this.loaded = false;

                this.params.status = this.filter;
                this.params.zone = this.zone;

                if (this.keywords) {
                    this.params.keywords = this.keywords;
                    UrlHelper.setParam('keywords', this.keywords);
                } else {
                    UrlHelper.setParam('keywords', '');
                }

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
            search: _.debounce(function (){
                    this.loaded = false;
                    this.params['keywords'] = this.keywords;
                    this.params.page = 1;
                    this.loadOrders();
                }, 500
            ),
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.loaded = false;
                this.params.page = page;
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
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab" @click="filter = null">
                    All Orders
                </a>
            </li>
            <li role="presentation" v-for="(tab, handle) in favourites" :key="handle" :style="{
                'border-color' : tab.color
            }" :class="{'active' : filter == handle}">
                <a :href="'#' + handle" :aria-controls="handle" role="tab" data-toggle="tab" @click="filter = handle">
                    {{ tab.label }}
                </a>
            </li>
            <!-- <li role="presentation" :class="{'active' : filter == 'payment-received'}" class="live">
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
            </li> -->
        </ul>
        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-collections">

                <!-- Search Form -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <date-range-picker @update="filterDate" @clear="clearDates" :from="params.from" :to="params.to"></date-range-picker>
                        </div>
                        <div class="col-md-3">
                            <candy-select null-label="All shipping zones" :options="shippingZones" v-if="shippingZones.length" v-model="zone"></candy-select>
                        </div>
                        <div class="col-md-3">
                            <candy-select null-label="All order statuses" :options="statusSelect" v-if="statusSelect.length" v-model="filter"></candy-select>
                        </div>
                    </div>
                <div class="row">
                    <div :class="{'col-md-12' : !selected.length, 'col-md-10': selected.length}">
                       <table class="table table-striped collection-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="10%">Status</th>
                                    <th>Order Id</th>
                                    <th>Customer Name</th>
                                    <th>Contact Email</th>
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
                                <tr class="clickable" v-for="order in orders" :key="order.id">
                                    <td>
                                        <div class="checkbox">
                                            <input type="checkbox" :id="'coll' + order.id" :value="order.id" v-model="selected">
                                            <label :for="'coll' + order.id"><span class="check"></span></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span  class="order-status" :style="getStyles(order.status)">{{ status(order.status) }}</span>
                                    </td>
                                    <td @click="loadOrder(order.id)" >
                                        {{ order.reference }}
                                    </td>
                                    <td @click="loadOrder(order.id)" >
                                        {{ order.customer_name }}
                                    </td>
                                    <td @click="loadOrder(order.id)">
                                        {{ order.contact_details.email }}
                                    </td>
                                    <td @click="loadOrder(order.id)" >
                                        <span v-if="order.user">Account</span>
                                        <span v-else>Guest</span>
                                    </td>
                                    <td @click="loadOrder(order.id)" ><span v-html="localisedPrice(order.sub_total, order.currency)"></span></td>
                                    <td @click="loadOrder(order.id)" ><span v-html="localisedPrice(order.discount_total, order.currency)"></span></td>
                                    <td @click="loadOrder(order.id)" ><span v-html="localisedPrice(order.delivery_total, order.currency)" data-toggle="tooltip" data-placement="bottom" :title="order.shipping_method"></span></td>
                                    <td @click="loadOrder(order.id)" ><span v-html="localisedPrice(order.tax_total, order.currency)"></span></td>
                                    <td @click="loadOrder(order.id)" ><span v-html="localisedPrice(order.order_total, order.currency)"></span></td>
                                    <td @click="loadOrder(order.id)" >{{ order.currency }}</td>
                                    <td v-if="filter != 'awaiting-payment'" @click="loadOrder(order.id)" >
                                        <template v-if="getShippingZone(order)">
                                            {{ getShippingZone(order) }}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </td>
                                    <td v-if="filter != 'awaiting-payment'" @click="loadOrder(order.id)" >
                                        <template v-if="order.placed_at">
                                            {{ order.placed_at.date|formatDate('Do MMM YYYY, H:mm:ss') }}
                                        </template>
                                        <template v-else>
                                            -
                                        </template>
                                    </td>
                                    <td v-else @click="loadOrder(order.id)" >
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
                    <div class="col-md-2" v-if="selected.length">
                        <h4>Bulk Actions</h4>
                        <div class="form-group">
                            <label>
                                Update Status
                            </label>
                            <candy-order-status-select v-model="bulk.status"></candy-order-status-select>
                            <div class="checkbox">
                                <input type="checkbox" id="sendEmails" v-model="sendEmails" value="1">
                                <label for="sendEmails">
                                    <span class="check"></span> Send notification emails
                                </label>

                            </div>
                        </div>
                        <button class="btn btn-primary" @click="bulkSave" :disabled="bulkSaving">
                            <template v-if="bulkSaving">
                                <i class="fa fa-refresh fa-spin"></i> Saving
                            </template>
                            <template v-else>
                                Save Orders
                            </template>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>