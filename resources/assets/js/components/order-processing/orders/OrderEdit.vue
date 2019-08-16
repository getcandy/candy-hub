<script>
    import Orders from '../../../mixins/OrderMixin';
    import UpdateOrderStatus from './UpdateOrderStatus';
    import RefundTransaction from './RefundTransaction';
    import OrderNote from './OrderNote';

    export default {
        mixins: [Orders],
        components: {
            UpdateOrderStatus,
            RefundTransaction,
            OrderNote,
        },
        data() {
            return {
                title: '',
                loaded: false,
                order: {},
                currency: {},
                currencies: [],
                transactions: {},
                paymentProviders: [],
                showStatusModal: false,
                dirty: false,
                initialFields : {
                    tracking_no: null,
                    status: null
                },
                hubPrefix: window.hubPrefix
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
            this.getStatuses();
        },
        mounted() {
            CandyEvent.$on('order-updated', event => {
                this.loaded = false;
                this.loadOrder(this.id);
            });
            Dispatcher.add('save-order', this);

            apiRequest.send('GET', '/payments/providers').then(response => {
                this.paymentProviders = response;
            })

        },
        computed: {
            maxRefund() {
                let transactions = 0;

                _.each(this.order.transactions.data, item => {
                    if (item.success) {
                        transactions += item.amount;
                    }
                });

                return transactions;
            },
            shippingZone() {
                return 1;
            },
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
                return _.filter(this.order.lines.data, line => {
                    return line.is_shipping;
                });
            },
        },
        methods: {
            canRefund(transaction) {
                return this.maxRefund &&
                    this.paymentProviders[transaction.driver] &&
                    !transaction.refund &&
                    transaction.success;
            },
            refreshState() {
                let dirtyFields = _.filter(this.initialFields, (value, field) => {
                    return this.order[field] && this.order[field] != value;
                });

                this.dirty = dirtyFields.length;
            },
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
            updateStatus(event) {
                this.saving = true;
                this.showStatusModal = true;
                apiRequest.send('PUT', '/orders/' + this.order.id, {
                    status: this.order.status,
                    send_emails: event.sendEmails,
                    data: {
                        content: event.text
                    }
                }).then(response => {
                    this.showStatusModal = false;
                    this.saving = false;
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Order Status Updated'
                    });
                })
            },
            save() {
                apiRequest.send('PUT', '/orders/' + this.order.id, {
                    tracking_no: this.order.tracking_no
                }).then(response => {
                    this.initialFields.tracking_no = this.order.tracking_no ? this.order.tracking_no : ' ';
                    this.initialFields.status = this.order.status;

                    this.refreshState();

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
                    includes: 'user.details,lines,transactions,discounts,shipping'
                })
                .then(response => {
                    this.order = response.data;

                    this.transactions = response.data.transactions.data;

                    CandyEvent.$emit('title-changed', {
                        prefix: 'Order for ',
                        title: this.order.display_id
                    });

                    this.initialFields.tracking_no = this.order.tracking_no ? this.order.tracking_no : ' ';
                    this.initialFields.status = this.order.status;

                    document.title = this.order.reference + ' Order - GetCandy';

                    apiRequest.send('GET', 'currencies/' + this.order.currency).then(response => {
                        this.currency = response.data;
                        this.loaded = true;
                    });
                }).catch(error => {
                });
            },
            customerLink(user) {
                return '/'+ this.hubPrefix +'/order-processing/customers/' + user.id;
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
        <template v-if="loaded">

            <div class="row">
                <div class="col-md-12 text-right">
                    <a :href="customerLink(order.user.data)" class="btn   btn-primary" v-if="order.user.data">View Customer Account</a>
                    <a :href="'/'+ hubPrefix +'/order-processing/orders/'+ order.id +'/invoice'" target="_blank" class="btn  btn-primary">Download Invoice</a>
                    <!-- <button @click="showStatusModal = true" class="btn  btn-primary">Update Status</button> -->
                    <update-order-status :order-id="order.id" :saving="showStatusModal" :show-modal="showStatusModal" :statuses="statuses" v-model="order.status" @save="updateStatus"></update-order-status>
                    <order-note :id="order.id"></order-note>
                </div>
            </div>
            <hr>

                    <div class="alert alert-warning text-center" v-if="dirty">
                        <i class="fa fa-danger"></i> You have unsaved changes
                    </div>
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Order ID</strong> <br>
                                    {{ order.display_id }}</span>
                                </div>
                                <div class="col-md-2">
                                    <strong>Order Reference</strong><br>
                                    <span>{{ order.reference }}</span> <candy-clipboard-copy :text="order.reference" />
                                </div>
                                <div class="col-md-2">
                                    <strong>Customer Reference</strong> <br>
                                    <span ref="customerRef">{{ order.customer_reference ? order.customer_reference : '-' }}</span> <candy-clipboard-copy :text="order.customer_reference" v-if="order.customer_reference"/>
                                </div>
                                <div class="col-md-2">
                                    <strong>Date Created</strong><br>
                                    {{ order.created_at|formatDate }}
                                </div>
                                <div class="col-md-2" v-if="order.placed_at">
                                    <strong>Payment Date</strong><br>
                                    {{ order.placed_at|formatDate }}
                                </div>
                                <div class="col-md-2" v-if="order.placed_at">
                                    <strong>Status</strong><br>
                                    {{ status(order.status) }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong style="margin-bottom:5px;display:block;">Billing info</strong>
                                    {{ order.billing_details.firstname }} {{ order.billing_details.lastname }}<br>
                                    {{ order.billing_details.address }}<br>
                                    {{ order.billing_details.address_two }}<br v-if="order.billing_details.address_two">
                                    {{ order.billing_details.address_three }}<br v-if="order.billing_details.address_three">
                                    {{ order.billing_details.city }}<br>
                                    {{ order.billing_details.county }}<br v-if="order.billing_details.county">
                                    {{ order.billing_details.state }}<br v-if="order.billing_details.state">
                                    {{ order.billing_details.country }}<br>
                                    {{ order.billing_details.zip }} <br>
                                    {{ order.billing_details.phone }} <br>
                                    {{ order.billing_details.email }}
                                </div>
                                <div class="col-md-4">
                                    <strong style="margin-bottom:5px;display:block;">Shipping info</strong>
                                    {{ order.shipping_details.firstname }} {{ order.shipping_details.lastname }}<br>
                                    {{ order.shipping_details.address }}<br>
                                    {{ order.shipping_details.address_two }}<br v-if="order.shipping_details.address_two">
                                    {{ order.shipping_details.address_three }}<br v-if="order.shipping_details.address_three">
                                    {{ order.shipping_details.city }}<br>
                                    {{ order.shipping_details.county }}<br v-if="order.shipping_details.county">
                                    {{ order.shipping_details.state }}<br v-if="order.shipping_details.state">
                                    {{ order.shipping_details.country }}<br>
                                    {{ order.shipping_details.zip }} <br>
                                    {{ order.billing_details.phone }} <br>
                                    {{ order.billing_details.email }}
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <strong style="margin-bottom:5px;display:block;">Contact Information</strong>
                                        Email: {{ order.contact_details.email }} <span class="text-muted" v-if="!order.contact_details.email">Not provided</span> <br>
                                        Telephone:
                                            <span v-if="order.contact_details.phone">{{ order.contact_details.phone }}</span>
                                            <span v-else-if="order.billing_details.phone">{{ order.billing_details.phone }}</span>
                                            <span v-else-if="order.shipping_details.phone">{{ order.shipping_details.phone }}</span>
                                            <span class="text-muted" v-else>Not provided</span>
                                    </p>
                                    <template v-if="has(order, 'user.data.details.data.fields.account_number') &&
                                              get(order, 'user.data.details.data.fields.account_number') != 0">
                                        <p><strong style="margin:10px 0 5px 0;display:block;">Account Number</strong>
                                        {{ get(order, 'user.data.details.data.fields.account_number') }}</p>
                                    </template>
                                    <strong style="margi:10px  0;display:block;">Tracking Number</strong>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="form-control" v-model="order.tracking_no" @keyup="refreshState">
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-primary" @click="save">Update Tracking</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row" v-if="order.shipping">
                                        <div class="col-md-4">
                                            <h4>Shipping Method</h4>
                                            <strong class="text-info">
                                                {{ order.shipping_details.method }}
                                            </strong>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Shipping Zone</h4>
                                            <strong class="text-info">
                                                {{ order.shipping.option }}
                                            </strong>
                                        </div>
                                        <div class="col-md-4" v-if="order.shipping_preference">
                                            <h4>Shipping Preference</h4>
                                            <strong class="text-info">
                                                {{ order.shipping_preference }}
                                            </strong>
                                        </div>
                                    </div>
                                    <hr>
                                    <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Product</th>
                                            <th>Variant</th>
                                            <th>QTY</th>
                                            <th>Unit Price</th>
                                            <th>Shipping Total</th>
                                            <th>Discount</th>
                                            <th>Tax Rate</th>
                                            <th>Tax Amount</th>
                                            <th>Line total</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td colspan="2" align="right"><strong>Sub total (Excl VAT)</strong></td>
                                            <td v-html="currencySymbol(order.sub_total)"></td>
                                        </tr>
                                        <template v-if="shipping.length">
                                            <tr v-for="line in shipping" :key="line.id">
                                                <td>-</td>
                                                <td>
                                                    {{ line.description }}
                                                </td>
                                                <td>
                                                    {{ line.variant_name }}
                                                </td>
                                                <td>-</td>
                                                <td v-html="currencySymbol(line.unit_cost)" v-if="line.unit_cost"></td>
                                                <td v-else>1</td>
                                                <td>-</td>
                                                <td v-html="currencySymbol(line.discount_total ? line.discount_total : 0)"></td>
                                                <td><span v-if="line.tax_total">VAT @ {{ line.tax_rate }}%</span><span v-else>-</span></td>
                                                <td v-html="currencySymbol(line.tax_total)"></td>
                                                <td v-html="currencySymbol(line.line_total)"></td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td colspan="2" align="right"><strong>Delivery Total (Excl Tax)</strong></td>
                                                <td v-html="currencySymbol(order.delivery_total)"></td>
                                            </tr>
                                        </template>
                                        <!-- <tr v-if="shipping">
                                            <td>-</td>
                                            <td>
                                                {{ shipping.description }}
                                            </td>
                                            <td>
                                                {{ shipping.variant_name }}
                                            </td>
                                            <td>-</td>
                                            <td>{{ shipping.unit_cost }}</td>
                                            <td>{{ shipping.discount_total }}</td>
                                            <td><span v-if="shipping.tax_total">VAT @ {{ shipping.tax_rate }}%</span><span v-else>-</span></td>
                                            <td v-html="currencySymbol(shipping.tax_total)"></td>
                                            <td v-html="currencySymbol(shipping.line_total)"></td>
                                        </tr> -->
                                        <!-- <tr v-else>
                                            <td colspan="6"></td>
                                            <td colspan="2" align="right"><strong>Delivery Total (Excl Tax)</strong></td>
                                            <td v-html="currencySymbol(order.delivery_total)"></td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="7"></td>
                                            <td colspan="2" align="right"><strong>VAT</strong></td>
                                            <td v-html="currencySymbol(order.tax_total)"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td colspan="2" align="right"><strong>Total</strong></td>
                                            <td v-html="currencySymbol(order.order_total)"></td>
                                        </tr>
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
                                            <td>{{ line.variant_name ? line.variant_name : '-' }}</td>
                                            <td>{{ line.quantity }}</td>
                                            <td v-html="currencySymbol(line.unit_price)"></td>

                                            <td v-html="currencySymbol(line.delivery_total)" v-if="line.delivery_total"></td>
                                            <td v-else>-</td>

                                            <td>
                                                <template v-if="line.discount_total">
                                                    <span class="text-danger" v-html="currencySymbol(-line.discount_total)"></span>
                                                </template>
                                                <template v-else>
                                                    -
                                                </template>
                                            </td>

                                            <td><span v-if="line.tax_total">VAT @ {{ line.tax_rate }}%</span><span v-else>-</span></td>
                                                <td v-html="currencySymbol(line.tax_total)"></td>
                                                <td v-html="currencySymbol(line.line_total - line.discount_total)"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <article v-if="order.notes">
                                <h3>Order Notes</h3>
                                <p>{{ order.notes }}</p>
                            </article>

                            <div class="row" v-if="order.discounts.data && order.discounts.data.length">
                                <div class="col-md-12">
                                    <h3>Discounts Applied</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Value</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="discount in order.discounts.data" :key="discount.id">
                                                <td>{{ discount.name }}</td>
                                                <td>{{ discount.type }}</td>
                                                <td>{{ discount.coupon }}</td>
                                                <td v-html="currencySymbol(discount.amount)"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <template v-if="transactions.length">
                    <h3>Transactions</h3>
                        <div class="transaction-panel" v-for="t in transactions" :key="t.id" :class="{
                            'transaction-charge' : !t.refund,
                            'transaction-refund': t.refund,
                            'transaction-success': t.success
                        }">
                            <header>
                                <span v-if="t.refund">Refund</span>
                                <span v-else>Charge</span>
                            </header>
                            <div class="panel-inner">
                                <div class="row">
                                    <div class="col-md-1">
                                        <strong>Status</strong><br>
                                        <span class="text-success" v-if="t.success">Processed</span>
                                        <span class="text-info" v-else-if="t.status == 'voided'">Voided</span>
                                        <span class="text-danger" v-else>Failed</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Transaction ID</strong><br>
                                        {{ t.transaction_id }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Date</strong><br>
                                        {{ t.created_at|formatDate }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Merchant</strong><br>
                                        {{ t.merchant }} / {{ t.provider }}
                                    </div>
                                    <div class="col-md-1">
                                        <strong>Amount</strong><br>
                                        <span v-html="currencySymbol(t.amount)"></span>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <template v-if="canRefund(t)">
                                            <refund-transaction :initial="t.amount / 100" :reference="t.transaction_id" :max="maxRefund / 100" :id="t.id" @refunded="loadOrder"></refund-transaction>
                                        </template>
                                    </div>
                                </div>

                                <hr>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Payment Type</strong><br>
                                                <span v-if="t.provider == 'paypal_account'">
                                                    PayPal
                                                </span>
                                                <span v-else>Credit card</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Card Type</strong><br>
                                                {{ t.card_type }}
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Last Four</strong><br>
                                                <template v-if="t.last_four">
                                                    <p class="card-no">&#42;&#42;&#42;&#42; &#42;&#42;&#42;&#42; &#42;&#42;&#42;&#42; {{ t.last_four }}</p>
                                                </template>
                                                <template v-else>
                                                    N/A
                                                </template>
                                            </div>
                                        </div>
                                        <strong>Provider Response</strong><br>
                                        {{ t.status }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Auth Checks</strong><br>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>CVC</span><br>
                                                <template v-if="t.cvc_matched">
                                                    <i class="fa fa-check text-success"></i>
                                                </template>
                                                <template v-else>
                                                    <i class="fa fa-times text-danger"></i>
                                                </template>
                                            </div>

                                            <div class="col-md-3">
                                                <span>Address</span><br>
                                                <template v-if="t.address_matched">
                                                    <i class="fa fa-check text-success"></i>
                                                </template>
                                                <template v-else>
                                                    <i class="fa fa-times text-danger"></i>
                                                </template>
                                            </div>

                                            <div class="col-md-3">
                                                <span>Postcode</span><br>
                                                <template v-if="t.postcode_matched">
                                                    <i class="fa fa-check text-success"></i>
                                                </template>
                                                <template v-else>
                                                    <i class="fa fa-times text-danger"></i>
                                                </template>
                                            </div>

                                            <div class="col-md-3">
                                                <span>3DSecure</span><br>
                                                <template v-if="t.threed_secure">
                                                    <i class="fa fa-check text-success"></i>
                                                </template>
                                                <template v-else>
                                                    <i class="fa fa-times text-danger"></i>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <template v-if="t.notes">
                                    <hr>
                                    <strong>Notes</strong><br>
                                    {{ t.notes }}
                                </template>
                            </div>
                        </div>
                    </template>
                    <candy-activity-log type="order" :id="order.id"></candy-activity-log>
        </template>

        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
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
