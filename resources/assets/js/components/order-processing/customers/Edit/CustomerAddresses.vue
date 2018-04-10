<script>
    export default {
        data() {
            return {
                editing: false,
                title: 'Editing Address',
                type: 'shipping',
                creating : false,
                model: {}
            }
        },
        props: ['addresses', 'customer'],
        methods: {
            created() {
                this.resetModel();
            },
            save() {
                this.model.shipping = this.type == 'shipping' ? true : false;
                this.model.billing = this.type == 'billing' ? true : false;
                this.model.user_id = this.customer.id;

                if (this.creating) {
                    apiRequest.send('POST', '/addresses', this.model).then(response => {
                        this.addresses.push(response.data);
                    });
                } else {
                  apiRequest.send('PUT', '/addresses/' + this.model.id, this.model);
                }

                CandyEvent.$emit('notification', {
                  level: 'success'
                });
                this.resetModel();
                this.editing = false;
            },
            create() {
                this.editing = true;
                this.title = 'Create Address';

                if (!this.creating) {
                    this.resetModel();
                }
                this.creating = true;
            },
            edit(item) {
                this.creating = false;
                var address = this.addresses[item];
                this.model = address;
                if (address.shipping) {
                    this.type = 'shipping';
                } else {
                    this.type = 'billing';
                }

                this.editing = true;
                this.title = "Edit Address";
            },
            remove(index) {
                if (!confirm('Are you sure?')) {
                  return false;
                }
              const id = this.customer.addresses.data[index].id;
              apiRequest.send('DELETE', 'addresses/' + id).then( response => {
                  this.customer.addresses.data.splice(index, 1);
                  CandyEvent.$emit('notification', {
                      level: 'success',
                      message: 'Address Deleted'
                  });
              });
            },
            resetModel() {
                this.model = {
                    firstname: '',
                    lastname: '',
                    address: '',
                    address_two: '',
                    address_three: '',
                    city: '',
                    county: '',
                    state: '',
                    zip: '',
                    country: '',
                    shipping: true,
                    billing: false
                };

                this.model.firstname = this.customer.details.data.firstname;
                this.model.lastname = this.customer.details.data.lastname;
            }
        }
    }
</script>

<template>
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Address</th>
                    <th>Address line 2</th>
                    <th>Address line 3</th>
                    <th width="10%">City</th>
                    <th>County</th>
                    <th width="10%">State</th>
                    <th>Zip</th>
                    <th>Country</th>
                    <th>Shipping / Billing</th>
                    <th width="7%"></th>
                </tr>
            </thead>
            <tfoot>
                <tr v-if="!addresses.length">
                    <td colspan="25" class="text-muted text-center">
                        {{ customer.name }} has no addresses listed on their acount
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr v-for="(address, index) in addresses">
                    <td>{{ address.firstname }}</td>
                    <td>{{ address.lastname }}</td>
                    <td>{{ address.address }}</td>
                    <td>{{ address.address_two }}</td>
                    <td>{{ address.address_three }}</td>
                    <td>{{ address.city }}</td>
                    <td>{{ address.county }}</td>
                    <td>{{ address.state }}</td>
                    <td>{{ address.zip }}</td>
                    <td>{{ address.country }}</td>
                    <td>
                        <span class="text-info" v-if="address.shipping">Shipping</span>
                        <span class="text-warning" v-if="address.billing">Billing</span>
                    </td>
                    <td>
                        <button class="btn btn-action btn-default" @click="edit(index)"><fa icon="edit"></fa></button>
                        <button class="btn btn-action btn-default" @click="remove(index)"><fa icon="trash-alt"></fa></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button class="btn btn-primary" @click="create"><fa icon="plus"></fa> Add Address</button>
        <hr>
        <candy-modal id="editAddressModel" :title="title" size="modal-md" v-show="editing" @closed="editing = false">

            <div slot="body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First name</label>
                            <input v-model="model.firstname" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last name</label>
                            <input v-model="model.lastname" class="form-control">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address Line 1</label>
                            <input v-model="model.address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address Line 2</label>
                            <input v-model="model.address_two" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address Line 3</label>
                            <input v-model="model.address_three" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>City</label>
                            <input v-model="model.city" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>County</label>
                            <input v-model="model.county" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>State</label>
                            <input v-model="model.state" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zip</label>
                            <input v-model="model.zip" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input v-model="model.country" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" v-model="type">
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Save Address</button>
            </div>

        </candy-modal>
    </div>
</template>