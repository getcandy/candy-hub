<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: false,
                customer: this.baseCustomer()
            }
        },
        methods: {
            save() {
                this.request.send('post', '/users', this.customer)
                .then(response => {
                    window.location = '/order-processing/customers/' + response.data.id;
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseCustomer() {
                return {
                    name: {
                        'firstname' : null,
                        'lastname' : null,
                        'password': null,
                        'password_confirmation' : null,
                        'email' : null
                    }
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="create = true"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Add Customer</button>
        <candy-modal title="Create Customer" v-show="create" size="modal-md" @closed="create = false">
            <div slot="body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstnamer">First name</label>
                            <input type="text" class="form-control" id="firstnamer" v-model="customer.firstname" @input="request.clearError('firstname')">
                            <span class="text-danger" v-if="request.getError('firstname')" v-text="request.getError('firstname')"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastname">Last name</label>
                            <input type="text" class="form-control" id="lastname" v-model="customer.lastname" @input="request.clearError('lastname')">
                            <span class="text-danger" v-if="request.getError('lastname')" v-text="request.getError('lastname')"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" v-model="customer.email" @input="request.clearError('email')">
                    <span class="text-danger" v-if="request.getError('email')" v-text="request.getError('email')"></span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" v-model="customer.password" @input="request.clearError('password')">
                            <span class="text-danger" v-if="request.getError('password')" v-text="request.getError('password')"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Password Confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation" v-model="customer.password_confirmation" @input="request.clearError('password')">
                        </div>
                    </div>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Save and Continue</button>
            </template>
        </candy-modal>
    </div>
</template>
