<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: false,
                method: this.baseMethod(),
                channel: this.$store.getters.getDefaultChannel.handle
            }
        },
        mounted() {
        },
        computed: {
            name: {
                get() {
                    return this.method.name[locale.current()];
                },
                set(value) {
                    this.method.name[locale.current()] = value;
                }
            }
        },
        methods: {
            save() {
                this.request.send('post', '/shipping', this.method)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.create = false;
                    this.method = this.baseMethod();
                    CandyEvent.$emit('shipping-method-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseMethod() {
                return {
                    name: {
                        [locale.current()] : ''
                    },
                    type: 'standard'
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="create = true"><fa icon="plus" /> Add Shipping Method</button>
        <candy-modal title="Create Shipping Method" v-show="create" size="modal-md" @closed="create = false">
            <div slot="body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" v-model="name" @input="request.clearError('name')">
                    <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="selectpicker form-control" v-model="method.type">
                        <option value="standard">Standard</option>
                        <option value="dhl" disabled>DHL</option>
                    </select>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create method</button>
            </template>
        </candy-modal>
    </div>
</template>
