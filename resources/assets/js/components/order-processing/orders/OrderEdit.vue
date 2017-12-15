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
                transactions: {}
            }
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadOrder(this.id);
        },
        mounted() {
            CandyEvent.$on('order-updated', event => {
                this.loaded = false;
                this.loadOrder(this.id);
            });
        },
        methods: {
            currencySymbol(total) {
                return this.currency.format.replace('{price}', total.money());
                // return 'ho';
            },
            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadOrder(id) {
                apiRequest.send('get', '/orders/' + id, {}, {
                    includes: 'user,lines,transactions'
                })
                .then(response => {
                    this.order = response.data;
                    this.transactions = response.data.transactions.data;

                    CandyEvent.$emit('title-changed', {
                        prefix: 'Order for ',
                        title: this.order.billing.firstname + ' ' + this.order.billing.lastname
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
                <candy-tabs>
                    <candy-tab name="Order Details" handle="collection-details" :selected="true">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <strong>Order Status:</strong> <span :class="status(order).class">{{ status(order).text }}</span><br>
                                        <strong>Account: </strong>
                                        <span v-if="!order.user">Guest</span>
                                        <span v-else>{{ order.user.name }}</span>
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
                                                            <span class="text-danger" v-else>Failed</span>
                                                        </td>
                                                        <td v-html="currencySymbol(item.amount)"></td>
                                                        <td>
                                                        {{ item.status }}
                                                        </td>
                                                        <td>{{ item.notes }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-small btn-danger" v-if="item.status == 'submitted_for_settlement'">Void</button>
                                                            <button @click="refund(index)" type="button" class="btn btn-small btn-info" v-if="item.status == 'settled' && !item.refunding">Issue Refund</button>
                                                            <button  type="button" class="btn btn-small btn-warning" v-if="item.refunding" disabled>
                                                                <i class="fa fa-refresh fa-spin"></i> Refunding
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </template>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Billing Information</h3>
                                        <hr>
                                        <strong>Name</strong><br>
                                        {{ order.billing.firstname }} {{ order.billing.lastname }}<br><br>
                                        <strong>Address</strong><br>
                                        {{ order.billing.address }}<br>
                                        {{ order.billing.address_two }}<br v-if="order.billing.address_two">
                                        {{ order.billing.address_three }}<br v-if="order.billing.address_three">
                                        {{ order.billing.city }}<br>
                                        {{ order.billing.county }}<br v-if="order.billing.county">
                                        {{ order.billing.state }}<br v-if="order.billing.state">
                                        {{ order.billing.country }}<br>
                                        {{ order.billing.zip }}

                                        <h3>Shipping Information</h3>
                                        <hr>
                                        <strong>Name</strong><br>
                                        {{ order.shipping.firstname }} {{ order.shipping.lastname }}<br><br>
                                        <strong>Address</strong><br>
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
