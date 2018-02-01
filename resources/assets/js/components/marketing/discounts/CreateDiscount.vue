<script>
    export default {
        data() {
            return {
                request : apiRequest,
                create: false,
                discount: this.baseDiscount(),
                channel: this.$store.getters.getDefaultChannel.handle
            }
        },
        computed: {
            name: {
                get() {
                    return this.discount.name[locale.current()];
                },
                set(value) {
                    this.discount.name[locale.current()] = value;
                }
            },
        },
        methods: {
            save() {
                this.request.send('post', '/discounts', this.discount)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.create = false;
                    this.discount = this.baseDiscount();
                    CandyEvent.$emit('discount-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseDiscount() {
                return {
                    name: {
                        [locale.current()] : ''
                    }
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="create = true"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Add Discount</button>
        <candy-modal title="Create Product" v-show="create" size="modal-md" @closed="create = false">
            <div slot="body">
                <div class="form-group">
                    <label for="name">Discount name</label>
                    <input type="text" class="form-control" id="name" v-model="name" @input="request.clearError('name')">
                    <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                </div>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create Discount</button>
            </template>
        </candy-modal>
    </div>
</template>
