<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: true,
                fields: {},
                customer: this.baseCustomer()
            }
        },
        mounted() {
            // Get any custom fields.
            this.request.send('get', '/users/fields').then(response => {
                this.fields = response.data.fields;
                _.each(this.fields, (field, index) => {
                    this.$set(this.customer.fields, index, null);
                });
            });
        },
        methods: {
            save() {
                this.request.send('post', '/users', this.customer)
                .then(response => {
                    window.location = route('hub.customers.edit', response.data.id);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseCustomer() {
                return {
                    'firstname' : null,
                    'lastname' : null,
                    'password': null,
                    'password_confirmation' : null,
                    'email' : null,
                    'fields' : {},
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
                {{ request.getError('email') }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstnamer">First name</label>
                            <input type="text" class="form-control" id="firstnamer" v-model="customer.firstname" @input="request.clearError('firstname')">
                            <span class="text-danger" v-text="error" v-for="error in request.getError('firstname')" :key="error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastname">Last name</label>
                            <input type="text" class="form-control" id="lastname" v-model="customer.lastname" @input="request.clearError('lastname')">
                            <span class="text-danger" v-text="error" v-for="error in request.getError('lastname')" :key="error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" v-model="customer.email" @input="request.clearError('email')">
                    <span class="text-danger" v-text="error" v-for="error in request.getError('email')" :key="error"></span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" v-model="customer.password" @input="request.clearError('password')">
                            <span class="text-danger" v-text="error" v-for="error in request.getError('password')" :key="error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Password Confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation" v-model="customer.password_confirmation" @input="request.clearError('password')">
                        </div>
                    </div>
                </div>
                    <div class="form-group" v-for="(field, handle) in fields" :key="handle">
                        <label :for="handle">{{ field.label }}</label>
                        <input :id="handle" class="form-control" :type="field.type ? field.type : 'text'" v-model="customer.fields[handle]">
                    </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Save and Continue</button>
            </template>
        </candy-modal>
    </div>
</template>
