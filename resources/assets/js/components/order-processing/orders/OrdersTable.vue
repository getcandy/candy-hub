<script>
    import Orders from '../../../mixins/OrderMixin';
    import DateRangePicker from '../../elements/forms/inputs/DateRangePicker';
    import UrlHelper from '../../../classes/UrlHelpers';
    import UpdateOrderStatus from './UpdateOrderStatus';

    export default {
        mixins: [Orders],
        components: {
            DateRangePicker,
            UpdateOrderStatus
        },
        data() {
            return {
                loaded: false,
                orders: [],
                selected: [],
                zone: null,
                checkedCount: 0,
                bulkSaving:false,
                sendEmails: true,
                shippingZones: [],
                type: null,
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
                    includes: 'user.details,shipping,lines',
                    from: null,
                    to: null
                },
                pagination: {},
                getters: {
                    name(order) {
                        const firstname = order.shipping_details.firstname ? order.shipping_details.firstname : null;
                        const lastname = order.shipping_details.lastname ? order.shipping_details.lastname : null;
                        return firstname ? firstname + ' ' + lastname : null;
                    },
                    order_total(order, currencies) {
                        var currency = _.find(currencies, item => {
                            return item.code == order.currency;
                        });
                        return currency.format.replace('{price}', order.order_total.money(2, currency.thousand_point, currency.decimal_point));
                    },
                    delivery_total(order, currencies) {
                        var currency = _.find(currencies, item => {
                            return item.code == order.currency;
                        });
                        return currency.format.replace('{price}', order.delivery_total.money(2, currency.thousand_point, currency.decimal_point));
                    },
                    account(order) {
                        return order.user.data ? 'Account' : 'Guest';
                    },
                    zone(order) {
                        let shipping = _.find(order.lines.data, line => {
                            return line.is_shipping;
                        });
                        return shipping ? shipping.option : null;
                    },
                    account_no(order) {
                        const fields = _.get(order, 'order.user.data.details.data.fields');
                        return fields ? fields.account_number : '-';
                    },
                    contact_email(order) {
                        return order.contact_details.email;
                    },
                    date(order) {
                        let date = order.placed_at;
                        if (!date) {
                           date = order.created_at;
                        }
                        return moment(date.date).format('D/MM/YYYY');
                    }
                }
            }
        },
        watch: {
            zone() {
                UrlHelper.setParam('zone', this.zone);
                this.loadOrders();
                this.params.page = 1;
            },
            type() {
                UrlHelper.setParam('type', this.type);
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
            this.initConfig();

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
            this.getTypes();
        },
        computed: {
            allSelected() {
                return !this.orders.length ? false : this.orders.length == this.selected.length;
            },
            columns() {
                return _.map(this.config.table_columns, col => {
                    return col;
                })
            }
        },
        methods: {
            getColumn(col, order) {
                if (typeof this.getters[col] === "function") {
                    return this.getters[col](order, this.currencies);
                }
                return order[col];
            },
            order_total() {

            },
            isSelected(id) {
                return this.selected.contains(id);
            },
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
            bulkSave(event) {
                this.bulkSaving = true;
                apiRequest.send('POST', 'orders/bulk', {
                    orders: this.selected,
                    field: 'status',
                    value: event.status,
                    send_emails: event.sendEmails,
                    data: {
                        content: event.text
                    }
                }).then(response => {
                    this.bulkSaving = false;
                    this.loadOrders();
                    this.selected = [];
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Orders Updated'
                    });
                }).catch(response => {
                    this.bulkSaving = false;
                });

            },
            loadOrders() {
                this.loaded = false;

                this.params.status = this.filter;
                this.params.zone = this.zone;
                this.params.type = this.type;

                this.selected = [];

                if (this.keywords) {
                    this.params.keywords = this.keywords;
                    UrlHelper.setParam('keywords', this.keywords);
                } else {
                    UrlHelper.setParam('keywords', '');
                }

                apiRequest.send('get', '/orders', [], this.params)
                    .then(response => {
                        this.orders = response.data;
                        this.params.total_pages = response.meta.last_page;
                        this.params.current_page = response.meta.current_page;

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
            selectAll() {
                this.selected = [];
                _.each(this.orders, order => {
                    this.selected.push(order.id);
                });
            },
            selectNone() {
                this.selected = [];
            },
            toggleSelectAll() {
                if (this.allSelected) {
                    this.selectNone();
                } else {
                    this.selectAll();
                }
            },
            changePage(page) {
                this.loaded = false;
                this.params.page = page;
                this.loadOrders();
            },
            loadOrder: function (id, newTab) {
                if (newTab) {
                    window.open(route('hub.orders.edit', id));
                } else {
                    location.href = route('hub.orders.edit', id);
                }
            },
            localisedPrice(amount, currency) {
                var currency = _.find(this.currencies, item => {
                    return item.code == currency;
                });
                return currency.format.replace('{price}', amount.money(2, currency.thousand_point, currency.decimal_point));
            },
            has(obj, path) {
                return _.has(obj, path);
            },
            get(obj, path, defaultVal) {
                return _.get(obj, path, defaultVal);
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
                    <div class="col-md-2">
                        <candy-select null-label="All shipping zones" :options="shippingZones" v-if="shippingZones.length" v-model="zone"></candy-select>
                    </div>
                    <div class="col-md-2">
                        <candy-select null-label="All order statuses" :options="statusSelect" v-if="statusSelect.length" v-model="filter"></candy-select>
                    </div>
                    <div class="col-md-2">
                        <candy-select null-label="All order types" :options="typeSelect" v-if="typeSelect.length" v-model="type"></candy-select>
                    </div>
                </div>
                <!-- Bulk Actions -->
                <div class="row" v-if="selected.length">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <update-order-status :statuses="statuses" :saving="bulkSaving" :show-modal="bulkSaving" @save="bulkSave"></update-order-status>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped collection-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox no-mar">
                                                <input type="checkbox" id="selectAll" @change="toggleSelectAll" :checked="allSelected">
                                                <label for="selectAll"><span class="check"></span></label>
                                            </div>
                                        </th>
                                        <th width="10%" v-html="$t('orders.table.heading.status')"></th>
                                        <th v-for="col in columns" v-html="$t('orders.table.heading.' + col)" :key="col"></th>
                                    </tr>
                                </thead>
                                <tbody v-if="loaded">
                                    <tr class="clickable" :class="{'selected': isSelected(order.id)}" v-for="order in orders" :key="order.id">
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox" :id="'coll' + order.id" :value="order.id" v-model="selected">
                                                <label :for="'coll' + order.id"><span class="check"></span></label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="order-status" :style="getStyles(order.status)">{{ status(order.status) }}</span>
                                        </td>
                                        <td @click.cmd="loadOrder(order.id, true)" @click.ctrl="loadOrder(order.id, true)" @click.exact="loadOrder(order.id)"  v-for="col in columns" v-html="getColumn(col, order)">
                                        </td>
                                    </tr>


                                </tbody>
                                <tfoot class="text-center" v-else>
                                    <tr>
                                        <td colspan="25" style="padding:40px;">
                                            <div class="loading">
                                                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-center" v-if="orders.length && loaded">
                            <candy-table-paginate :pagination="params" @change="changePage"></candy-table-paginate>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>