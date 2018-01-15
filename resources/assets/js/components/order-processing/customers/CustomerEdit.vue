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
                customer: {},
            }
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadCustomer();
        },
        methods: {
            /**
             * Loads the customer by their ID
             * @param  {String} id
             */
            loadCustomer() {
                apiRequest.send('get', '/customers/' + this.id, {}, {
                    includes: 'addresses,orders'
                })
                .then(response => {
                    this.customer = response.data;
                    this.loaded = true;
                    CandyEvent.$emit('title-changed', {
                        title: this.customer.name
                    });
                }).catch(error => {
                });
            },
            viewOrder(id) {
                location.href = '/order-processing/orders/' + id;
            }
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">
            <candy-tabs>
                <candy-tab name="Customer Information" handle="collection-details" :selected="true">
                    <div class="sub-panel">
                        <div class="sub-content block section">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Customer Details</h4>
                                </div>
                            </div>
                                    <hr>
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Customer Name</strong> <br>
                                    {{ customer.name }}
                                </div>
                                <div class="col-md-2">
                                    <strong>Company name</strong> <br>
                                    {{ customer.company_name }}
                                </div>
                                <div class="col-md-2">
                                    <strong>Email address</strong> <br>
                                    {{ customer.email }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Addresses</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>First name</th>
                                                <th>Last name</th>
                                                <th>Address</th>
                                                <th>Address line 2</th>
                                                <th>Address line 3</th>
                                                <th>City</th>
                                                <th>County</th>
                                                <th>State</th>
                                                <th>Country</th>
                                                <th>Shipping / Billing</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr v-if="!customer.addresses.data.length">
                                                <td colspan="25" class="text-muted text-center">
                                                    {{ customer.name }} has no addresses listed on their acount
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr v-for="address in customer.addresses.data">
                                                <td>{{ address.firstname }}</td>
                                                <td>{{ address.lastname }}</td>
                                                <td>{{ address.address }}</td>
                                                <td>{{ address.address_two }}</td>
                                                <td>{{ address.address_three }}</td>
                                                <td>{{ address.city }}</td>
                                                <td>{{ address.county }}</td>
                                                <td>{{ address.state }}</td>
                                                <td>{{ address.country }}</td>
                                                <td>
                                                    <span class="text-info" v-if="address.shipping">Shipping</span>
                                                    <span class="text-warning" v-if="address.billing">Billing</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Order History</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Total</th>
                                                <th>Tax</th>
                                                <th>Date created</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr v-if="!customer.orders.data.length">
                                                <td colspan="25" class="text-muted text-center">
                                                    {{ customer.name }} has no order history
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr v-for="order in customer.orders.data">
                                               <td>
                                                   #{{ order.id }}
                                               </td>
                                               <td>{{ order.total }} {{ order.currency }}</td>
                                               <td>{{ order.vat }}</td>
                                               <td>{{ order.created_at.date|formatDate('dF m Y') }}</td>
                                               <td>
                                                   <button @click="viewOrder(order.id)" class="btn btn-action btn-default btn-sm">
                                                       <fa icon="eye"></fa>
                                                   </button>
                                               </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </candy-tab>
            </candy-tabs>
        </template>
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
