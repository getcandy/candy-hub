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
                customerGroups: [],
                selectedGroups: [],
                newPassword: null,
                confirmPassword: null,
                request: apiRequest
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
            this.request.send('get', 'customers/groups').then(response => {
                this.customerGroups = response.data;
            });
        },
        mounted() {
            Dispatcher.add('save-customer', this);
        },
        methods: {
            save() {

                let data = JSON.parse(JSON.stringify(this.customer));

                data.customer_groups = this.selectedGroups;

                if (this.newPassword && this.confirmPassword) {
                    data.password = this.newPassword;
                    data.password_confirmation = this.confirmPassword;
                }

                data.details = data.details.data;

                apiRequest.send('PUT', '/users/' + this.customer.id, data).then(response => {
                    this.newPassword = null;
                    this.confirmPassword = null;
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                }).catch(error => {
                    this.newPassword = null;
                    this.confirmPassword = null;
                });
            },
            details(customer) {
                 return customer.details.data;
            },
            /**
             * Loads the customer by their ID
             * @param  {String} id
             */
            loadCustomer() {
                apiRequest.send('get', '/customers/' + this.id, {}, {
                    includes: 'addresses,orders,groups,details'
                })
                .then(response => {
                    this.customer = response.data;
                    this.loaded = true;
                    this.selectedGroups = _.map(this.customer.groups.data, group => {
                        return group.id;
                    });
                    CandyEvent.$emit('title-changed', {
                        title: this.customer.firstname + ' ' + this.customer.lastname
                    });
                }).catch(error => {
                });

            },
            viewOrder(id) {
                location.href = route('hub.orders.edit', id);
            }
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">
            <candy-tabs initial="save-customer">
                <candy-tab name="Customer Information" handle="collection-details" :selected="true" dispatch="save-customer">
                    <div class="sub-panel">
                        <div class="sub-content block section">
                            <div class="row">
                                <div class="col-md-8">
                                        <h4>Customer Details</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>First name</label>
                                                <input class="form-control" v-model="customer.details.data.firstname">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Last name</label>
                                                <input class="form-control" v-model="customer.details.data.lastname">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" v-model="customer.email">
                                            </div>
                                            <span class="text-danger" v-if="request.hasError('email')">
                                                {{ request.getError('email') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input class="form-control" v-model="customer.details.data.company_name">
                                            </div>
                                            <span class="text-danger" v-if="request.hasError('company_name')">
                                                {{ request.getError('company_name') }}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input type="tel" class="form-control" v-model="customer.details.data.contact_number">
                                            </div>
                                            <span class="text-danger" v-if="request.hasError('contact_number')">
                                                {{ request.getError('contact_number') }}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>VAT Number</label>
                                                <input class="form-control" v-model="customer.details.data.vat_no">
                                            </div>
                                            <span class="text-danger" v-if="request.hasError('vat_no')">
                                                {{ request.getError('vat_no') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input class="form-control" type="password" v-model="newPassword">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>New Password confirmation</label>
                                                <input class="form-control" type="password" v-model="confirmPassword">
                                            </div>
                                            <span class="text-danger" v-if="request.hasError('password')">
                                                {{ request.getError('password') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4>Customer Groups</h4>
                                    <hr>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="group in customerGroups">
                                                <td>{{ group.name }}</td>
                                                <td><input type="checkbox" v-model="selectedGroups" :value="group.id"></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
