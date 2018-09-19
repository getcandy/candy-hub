<script>
    import Orders from '../../../mixins/OrderMixin';

    export default {
        mixins: [Orders],
        data() {
            return {
                title: '',
                loaded: false,
                order: {},
                currency: {},
                currencies: [],
                transactions: {},
                isMailable: false
            }
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadOrder();
        },
        mounted() {
            CandyEvent.$on('order-updated', event => {
                this.loaded = false;
                this.loadOrder(this.id);
            });
            Dispatcher.add('save-order', this);
        },
        computed: {
            discountTotal() {
                let total = 0;
                _.each(this.order.lines.data, line => {
                    total += line.discount;
                });
                return total;
            },
            productLines() {
                return _.filter(this.order.lines.data, line => {
                    return !line.is_shipping;
                });
            },
            shipping() {
                return _.find(this.order.lines.data, line => {
                    return line.is_shipping;
                });
            }
        },
        methods: {
            productLink(sku) {
                if (!sku) {
                    return '#';
                }
                return route('hub.products.edit', sku);
            },
            currencySymbol(total) {
                return this.currency.format.replace('{price}', total.money());
                // return 'ho';
            },
            unitPrice(line) {
                return line.line_amount / line.quantity;
            },
            setMailable() {
                if (this.order.status == 'dispatched') {
                    this.isMailable = true;
                } else {
                    this.isMailable = false;
                }
            },
            save() {
                apiRequest.send('PUT', '/orders/' + this.order.id, {
                    tracking_no: this.order.tracking_no,
                    status: this.order.status
                }).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Order saved'
                    });
                })
            },
            sendTracking() {
                apiRequest.send('POST', '/orders/' + this.order.id + '/sendtracking').then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Tracking email sent'
                    });
                })
            },
            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadOrder() {
                apiRequest.send('get', '/orders/' + this.id, {}, {
                    includes: 'user,lines,transactions,discounts,shipping'
                })
                .then(response => {
                    this.order = response.data;
                    this.transactions = response.data.transactions.data;

                    CandyEvent.$emit('title-changed', {
                        prefix: 'Order for ',
                        title: this.order.customer_name
                    });

                    apiRequest.send('GET', 'currencies/' + this.order.currency).then(response => {
                        this.currency = response.data;
                        this.loaded = true;
                    });
                }).catch(error => {
                });
            },
            refund(index) {
                if (!confirm('Are you sure?')) {
                    return false;
                }
                this.$set(this.transactions[index], 'refunding', true);

                var transaction = this.transactions[index];

                apiRequest.send('post', '/payments/' + transaction.id + '/refund').then(response => {
                    this.$set(this.transactions[index], 'refunding', false);
                    this.loadOrder();
                }).catch(response => {
                    this.$set(this.transactions[index], 'refunding', false);
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: error.message
                    });
                });

            },
            customerLink(user) {
                return '/order-processing/customers/' + user.id;
            },
            voidit(index) {
                if (!confirm('Are you sure?')) {
                    return false;
                }
                this.$set(this.transactions[index], 'voiding', true);

                var transaction = this.transactions[index];

                apiRequest.send('post', '/payments/' + transaction.id + '/void').then(response => {
                    this.loadOrder();
                }).catch(response => {
                    this.$set(this.transactions[index], 'voiding', false);
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: error.message
                    });
                });
            }
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">

            <transition name="fade">
                <candy-tabs initial="orderdetails">
                    <candy-tab name="Order Details" handle="collection-details" dispatch="save-order" :selected="true">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3>Order Lines</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>SKU</th>
                                                    <th>Product</th>
                                                    <th>Variant</th>
                                                    <th>QTY</th>
                                                    <th>Unit Price</th>
                                                    <th>Discount</th>
                                                    <th>Tax Rate</th>
                                                    <th>Tax Amount</th>
                                                    <th>Line total</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <!--  -->
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td colspan="2" align="right"><strong>Sub total (Excl VAT)</strong></td>
                                                    <td v-html="currencySymbol(order.sub_total)"></td>
                                                </tr>
                                                <tr>
                                                    <td>-</td>
                                                    <td>
                                                        {{ shipping.description }}
                                                    </td>
                                                    <td>
                                                        {{ shipping.variant }}
                                                    </td>
                                                    <td>-</td>
                                                    <td>{{ shipping.unit_cost }}</td>
                                                    <td>{{ shipping.discount_total }}</td>
                                                    <td><span v-if="shipping.tax_total">VAT @ {{ shipping.tax_rate }}%</span><span v-else>-</span></td>
                                                    <td v-html="currencySymbol(shipping.tax_total)"></td>
                                                    <td v-html="currencySymbol(shipping.line_total)"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td colspan="2" align="right"><strong>VAT</strong></td>
                                                    <td v-html="currencySymbol(order.tax_total)"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td colspan="2" align="right"><strong>Total</strong></td>
                                                    <td v-html="currencySymbol(order.order_total)"></td>
                                                </tr>
                                                <!-- <tr v-if="order.vat_no && order.billing.country != 'United Kingdom'">
                                                    <td colspan="5"></td>
                                                    <td colspan="4" align="right">
                                                        <span class="text-info">EU Reverse Charge VAT Applied</span>
                                                    </td>
                                                </tr> -->
                                            </tfoot>
                                            <tbody>
                                                <tr v-for="line in productLines" :key="line.id">
                                                    <td>
                                                        <template v-if="line.sku">
                                                        <a :href="productLink(line.sku)" target="_blank" :title="'View' + line.product">
                                                        {{ line.sku }}
                                                        </a>
                                                        </template>
                                                        <template v-else>
                                                            -
                                                        </template>
                                                    </td>
                                                    <td>
                                                        <template v-if="line.sku">
                                                            <a :href="productLink(line.sku)" target="_blank" :title="'View' + line.description">
                                                                {{ line.description }}
                                                            </a>
                                                        </template>
                                                        <template v-else>
                                                            {{ line.description }}
                                                        </template>

                                                    </td>
                                                    <td>{{ line.variant ? line.variant : '-' }}</td>
                                                    <td>{{ line.quantity }}</td>
                                                    <td v-html="currencySymbol(line.unit_price)"></td>
                                                    <td>
                                                        <template v-if="line.discount_total">
                                                            <span class="text-danger" v-html="currencySymbol(-line.discount_total + line.tax_total)"></span>
                                                        </template>
                                                        <template v-else>
                                                            -
                                                        </template>
                                                    </td>
                                                    <td><span v-if="line.tax_total">VAT @ {{ line.tax_rate }}%</span><span v-else>-</span></td>
                                                    <template v-if="line.discount_total">
                                                        <td v-html="currencySymbol(line.tax_total + (line.discount_total - (line.discount_total + line.tax_total)))"></td>
                                                        <td v-html="currencySymbol(line.line_total - (line.discount_total - line.tax_total))"></td>
                                                    </template>
                                                    <template v-else>
                                                        <td v-html="currencySymbol(line.tax_total)"></td>
                                                        <td v-html="currencySymbol(line.line_total)"></td>
                                                    </template>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <template v-if="transactions.length">
                                            <h3>Transactions</h3>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Merchant</th>
                                                        <th>Successful</th>
                                                        <th>Amount</th>
                                                        <th>Payment Type</th>
                                                        <th>Card type</th>
                                                        <th>Card number</th>
                                                        <th>Status</th>
                                                        <th>Notes</th>
                                                        <th width="8%">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in transactions">
                                                        <td>{{ item.transaction_id }}</td>
                                                        <td>{{ item.merchant }}</td>
                                                        <td>
                                                            <span class="text-success" v-if="item.success">Processed</span>
                                                            <span class="text-info" v-else-if="item.status == 'voided'">Voided</span>
                                                            <span class="text-danger" v-else>Failed</span>
                                                        </td>
                                                        <td v-html="currencySymbol(item.amount)"></td>
                                                        <td>
                                                            <span v-if="item.provider == 'paypal_account'">
                                                                PayPal
                                                            </span>
                                                            <span v-else>Credit card</span>
                                                        </td>
                                                        <td>{{ item.card_type }}</td>
                                                        <td>
                                                            <template v-if="item.last_four">
                                                                **** **** **** {{ item.last_four }}
                                                            </template>
                                                        </td>
                                                        <td>
                                                            {{ item.status }}
                                                        </td>
                                                        <td>{{ item.notes }}</td>
                                                        <td>
                                                            <button @click="voidit(index)" type="button" class="btn btn-small btn-danger" v-if="item.status == 'submitted_for_settlement' && !item.voiding">Void</button>
                                                            <button @click="refund(index)" type="button" class="btn btn-small btn-info" v-if="item.status == 'settled' && !item.refunding">Issue Refund</button>
                                                            <button  type="button" class="btn btn-small btn-warning" v-if="item.refunding" disabled>
                                                                <i class="fa fa-refresh fa-spin"></i> Refunding
                                                            </button>
                                                            <button  type="button" class="btn btn-small btn-warning" v-if="item.voiding" disabled>
                                                                <i class="fa fa-refresh fa-spin"></i> voiding
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </template>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Order ID</strong>
                                                <code style="padding:5px">{{ order.reference }}</code>
                                            </div>
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Order Date</strong>
                                                {{ order.created_at.date|formatDate }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row" v-if="order.vat_no">
                                            <div class="col-md-12">
                                                <strong>Customer VAT No.</strong> {{ order.vat_no }}
                                            </div>
                                        </div>
                                        <hr>
                                        <template v-if="order.invoice_reference">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a :href="'/order-processing/orders/'+ order.id +'/invoice'" target="_blank" class="btn btn-primary">Download Invoice</a>
                                                </div>
                                            </div>
                                            <hr>
                                        </template>
                                        <div class="form-group">
                                            <strong style="margin-bottom:5px;display:block;">Order status</strong>
                                            <select class="form-control" v-model="order.status" @change="setMailable">
                                                <option value="on-account">On Account</option>
                                                <option value="payment-processing">Payment Processing</option>
                                                <option value="awaiting-payment">Awaiting Payment</option>
                                                <option value="payment-received">Payment Received</option>
                                                <option value="in-progress">In Progress</option>
                                                <option value="dispatched">Dispatched</option>
                                                <option value="failed">Failed</option>
                                                <option value="voided">Voided</option>
                                                <option value="returned">Returned</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <strong style="margin-bottom:5px;display:block;">Tracking Number</strong>
                                            <input class="form-control" v-model="order.tracking_no">
                                        </div>

                                        <div class="alert alert-info" v-if="isMailable">
                                            <p>Saving will send a delivery email notification to <strong>{{ order.contact_email }}</strong></p>
                                        </div>

                                        <template v-if="order.dispatched_at">
                                            <button class="btn btn-primary" @click="sendTracking">Send dispatched email</button>
                                        </template>
                                        <hr>

                                        <strong style="margin-bottom:5px;display:block;">Account</strong>
                                        <span v-if="!order.user">Guest</span>
                                        <template v-else>
                                            {{ order.user.data.name }} <a :href="customerLink(order.user.data)" class="link">View</a>
                                        </template>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Email</strong>
                                                {{ order.contact_email }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Telephone</strong>
                                                {{ order.contact_phone }}
                                                <span class="text-muted" v-if="!order.contact_phone">Not provided</span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Billing info</strong>
                                                {{ order.billing_details.firstname }} {{ order.billing_details.lastname }}<br>
                                                {{ order.billing_details.address }}<br>
                                                {{ order.billing_details.address_two }}<br v-if="order.billing_details.address_two">
                                                {{ order.billing_details.address_three }}<br v-if="order.billing_details.address_three">
                                                {{ order.billing_details.city }}<br>
                                                {{ order.billing_details.county }}<br v-if="order.billing_details.county">
                                                {{ order.billing_details.state }}<br v-if="order.billing_details.state">
                                                {{ order.billing_details.country }}<br>
                                                {{ order.billing_details.zip }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Shipping info</strong>
                                                {{ order.shipping_details.firstname }} {{ order.shipping_details.lastname }}<br>
                                                {{ order.shipping_details.address }}<br>
                                                {{ order.shipping_details.address_two }}<br v-if="order.shipping_details.address_two">
                                                {{ order.shipping_details.address_three }}<br v-if="order.shipping_details.address_three">
                                                {{ order.shipping_details.city }}<br>
                                                {{ order.shipping_details.county }}<br v-if="order.shipping_details.county">
                                                {{ order.shipping_details.state }}<br v-if="order.shipping_details.state">
                                                {{ order.shipping_details.country }}<br>
                                                {{ order.shipping_details.zip }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </candy-tab>
                </candy-tabs>
            </transition>
        </template>

        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>

    </div>

</template>

<style lang="scss" scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
    {
        opacity: 0;
    }
</style>
