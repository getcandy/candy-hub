<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data() {
            return {
                title: '',
                loaded: false,
                order: {},
                currency: {},
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
        methods: {
            currencySymbol(total) {
                return this.currency.format.replace('{price}', total.money());
                // return 'ho';
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
                    includes: 'user,lines,transactions'
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
                    class: 'text-' + type,
                    text: text
                };
            },
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
                                                    <th>Line total</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><strong>VAT</strong></td>
                                                    <td v-html="currencySymbol(order.vat)"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><strong>Shipping</strong></td>
                                                    <td v-html="currencySymbol(order.shipping_total)"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><strong>Total</strong></td>
                                                    <td v-html="currencySymbol(order.total)"></td>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr v-for="line in order.lines.data">
                                                    <td>{{ line.sku }}</td>
                                                    <td>{{ line.product }}</td>
                                                    <td>{{ line.variant ? line.variant : '-' }}</td>
                                                    <td>{{ line.quantity }}</td>
                                                    <td v-html="currencySymbol(line.total)"></td>
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
                                        <br>
                                        <div class="form-group">
                                            <strong style="margin-bottom:5px;display:block;">Order status</strong>
                                            <select class="form-control" v-model="order.status" @change="setMailable">
                                                <option value="awaiting-payment">Awaiting Payment</option>
                                                <option value="payment-received">Payment Received</option>
                                                <option value="processing">Processing</option>
                                                <option value="dispatched">Dispatched</option>
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
                                        <br>
                                        <a :href="'/order-processing/orders/'+ order.id +'/invoice'">Download Invoice</a>
                                        <br><br>
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
                                                {{ order.billing.firstname }} {{ order.billing.lastname }}<br>
                                                {{ order.billing.address }}<br>
                                                {{ order.billing.address_two }}<br v-if="order.billing.address_two">
                                                {{ order.billing.address_three }}<br v-if="order.billing.address_three">
                                                {{ order.billing.city }}<br>
                                                {{ order.billing.county }}<br v-if="order.billing.county">
                                                {{ order.billing.state }}<br v-if="order.billing.state">
                                                {{ order.billing.country }}<br>
                                                {{ order.billing.zip }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong style="margin-bottom:5px;display:block;">Shipping info</strong>
                                                {{ order.shipping.firstname }} {{ order.shipping.lastname }}<br>
                                                {{ order.shipping.address }}<br>
                                                {{ order.shipping.address_two }}<br v-if="order.shipping.address_two">
                                                {{ order.shipping.address_three }}<br v-if="order.shipping.address_three">
                                                {{ order.shipping.city }}<br>
                                                {{ order.shipping.county }}<br v-if="order.shipping.county">
                                                {{ order.shipping.state }}<br v-if="order.shipping.state">
                                                {{ order.shipping.country }}<br>
                                                {{ order.shipping.zip }}
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
