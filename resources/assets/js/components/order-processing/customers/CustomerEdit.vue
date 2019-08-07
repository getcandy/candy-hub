<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
  import Orders from '../../../mixins/OrderMixin';
  import CustomerAddresses from './Edit/CustomerAddresses';

  export default {
      mixins: [Orders],
      components: {
          'customer-addresses' : CustomerAddresses
      },
      data() {
          return {
              title: '',
              loaded: false,
              customer: {},
              customerGroups: [],
              selectedGroups: [],
              config: {},
              ordersBatch: 1,
              newPassword: null,
              confirmPassword: null,
              ordersPerPage: 10,
              request: apiRequest,
              orders: []
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


          this.getStatuses();
      },
      mounted() {
          Dispatcher.add('save-customer', this);
      },
      methods: {
          formatLabel(value) {
              value = value.split('_').join(' ').toString();
              return value.charAt(0).toUpperCase() + value.slice(1);
          },
          loadConfig() {
              return apiRequest.send('GET', '/settings/users');
          },
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
                  this.selectedGroups = _.map(this.customer.groups.data, group => {
                      return group.id;
                  });

                  CandyEvent.$emit('title-changed', {
                      title: this.customer.details.data.firstname + ' ' + this.customer.details.data.lastname
                  });

                  let chunkedOrders = _.chunk(this.customer.orders.data, this.ordersPerPage);

                  _.each(chunkedOrders, (orders, index) => {
                      this.orders[index + 1] = orders;
                  });

                  document.title = 'Editing ' +
                    this.customer.details.data.firstname +
                    ' ' +
                    this.customer.details.data.lastname +
                    ' - GetCandy';

                  this.loadConfig().then(response => {
                    this.config = response.data;

                    if (this.config.fields) {
                      let fields = this.customer.details ? this.customer.details.data.fields : null;
                      if (!fields || _.isArray(fields)) {
                        fields = {};
                      }

                      _.each(this.config.fields, (field, handle) => {
                        if (!fields[handle]) {
                          _.set(fields, handle, "");
                        }
                      });

                      _.set(this.customer, 'details.data.fields', fields);
                    }

                    apiRequest.send('GET', 'currencies').then(response => {
                        this.currencies = response.data;
                        this.loaded = true;
                    });
                  }).catch(error => {
                  });



              }).catch(error => {
              });

          },
          getOrders(batch) {
              return this.orders[batch];
          },
          changeOrderBatch(batch) {
              this.ordersBatch = batch;
          },
          viewOrder(id) {
              return route('hub.orders.edit', id);
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
                                                <input autocomplete="new-password" class="form-control" type="password" v-model="newPassword">
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

                                        <template v-if="customer.details.data && customer.details.data.fields">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4>Additional Information</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4" v-for="(field, label) in customer.details.data.fields" :key="label">
                                                    <div class="form-group">
                                                        <label>{{ formatLabel(label) }}</label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            v-model="customer.details.data.fields[label]"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
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
                                                <td><label :for="group.name + 'Checkbox'">{{ group.name }}</label></td>
                                                <td><input type="checkbox" v-model="selectedGroups" :value="group.id" :id="group.name + 'Checkbox'"></td>
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
                                    <customer-addresses :addresses="customer.addresses.data" :customer="customer"></customer-addresses>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Order History</h4>
                                </div>

                            </div>
                            <hr>
                            <!-- <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="input-group input-group-full">
                                        <span class="input-group-addon">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                        <label class="sr-only" for="search">Search Orders</label>
                                        <input type="text" class="form-control" id="search" placeholder="Search by order ID" @keyup="search" v-model="searchedOrder">
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="10%">Status</th>
                                                <th>Order Id</th>
                                                <th>Order Reference</th>
                                                <th>Total</th>
                                                <th>Shipping</th>
                                                <th>Date Placed / Created</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr v-if="!customer.orders.data.length">
                                                <td colspan="25" class="text-muted text-center">
                                                    {{ customer.details.data.firstname }} has no order history
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr v-for="order in orders[ordersBatch]">
                                              <td>
                                                  <span  class="order-status" :style="getStyles(order.status)">{{ status(order.status) }}</span>
                                              </td>
                                              <td>
                                                  <a :href="viewOrder(order.id)" title="View order">
                                                    {{ order.display_id }}
                                                  </a>
                                              </td>
                                              <td>
                                                  {{ order.reference }}
                                              </td>
                                              <td>
                                                <span v-html="localisedPrice(order.order_total, order.currency)"></span>
                                              </td>
                                              <td>
                                                <span v-html="localisedPrice(order.delivery_total, order.currency)"></span>
                                              </td>
                                              <td>
                                                <span v-if="order.placed_at">{{ order.placed_at.date|formatDate }}</span>
                                                <span v-else>{{ order.created_at.date|formatDate }}</span>
                                              </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination" v-if="orders.length">
                                                <li v-if="ordersBatch !== 1">
                                                    <a href="#" aria-label="First page" data-toggle="tooltip" data-placement="top" data-original-title="First page" title="First page" @click.prevent="changeOrderBatch(1)">
                                                        <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                                                    </a>
                                                </li>
                                                <li v-if="ordersBatch > 1">
                                                    <a href="#" aria-label="Previous" data-toggle="tooltip" data-placement="top" data-original-title="Previous page" title="Previous page" @click.prevent="changeOrderBatch(ordersBatch - 1)">
                                                        <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                                    </a>
                                                </li>
                                                <li v-for="(orders, batch) in orders" v-bind:class="[ batch == ordersBatch ? 'active' : '']" v-if="batch > 0">
                                                    <a href="#" @click.prevent="changeOrderBatch(batch)">{{ batch }}</a>
                                                </li>
                                                <li v-if="ordersBatch < orders.length - 1">
                                                    <a href="#" aria-label="Next" data-toggle="tooltip" data-placement="top" data-original-title="Next page" title="Next page" @click.prevent="changeOrderBatch(ordersBatch + 1)">
                                                        <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                                    </a>
                                                </li>
                                                <li v-if="ordersBatch !== orders.length">
                                                    <a href="#" aria-label="Last page" data-toggle="tooltip" data-placement="top" data-original-title="Last page" title="Last page" @click.prevent="changeOrderBatch(orders.length - 1)">
                                                        <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
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
