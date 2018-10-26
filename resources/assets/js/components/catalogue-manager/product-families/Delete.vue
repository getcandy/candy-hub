<template>
    <div>
        <button class="btn btn-default white" @click="showModal = true" :disabled="!deletable"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
        <candy-modal title="Confirm deletion" v-show="showModal" size="modal-md" @closed="showModal = false">
            <div slot="body">
                <template v-if="family.product_count">
                <div class="alert alert-danger" v-if="!selected">
                    If you don't choose a product family to migrate to, all products associated to this product family will move to "default"
                </div>
                <p>Please select a product family to migrate the products too</p>
                <select class="form-control" v-model="selected">
                    <option v-for="family in filtered" :value="family.id" :key="family.id">
                        {{ family|attribute('name') }}
                    </option>
                </select>
                </template>
                <template v-else>
                    <p>Are you sure you want to remove this product family?</p>
                </template>
            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="trash">Delete {{ element }}</button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                request : apiRequest,
                selected: null,
                showModal: false,
                deletable: true,
                family: null,
                families: [],
            }
        },
        props : {
            warning: {
                type: String,
                default: null
            },
            endpoint : {
                type: String
            },
            element: {
                type: String
            },
            redirect: {
                type: String,
                default: '/'
            },
            id: {
                type: String
            }
        },
        mounted() {
            apiRequest.send('GET', '/product-families/' + this.id).then(response => {
                this.family = response.data;
            });
            apiRequest.send('get', '/product-families', [],{
                per_page: 200
            })
            .then(response => {
                this.families = response.data;

                if (this.families.length == 1) {
                    this.deletable = false;
                }

                const first = this.families[0];

                if (first.id == this.id) {
                    this.deletable = false;
                }

                this.selected = first.id;
            });
        },
        methods : {
            trash() {
                this.request.send('delete', '/product-families/' + this.id, {
                    product_family_id: this.selected,
                })
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    window.location = '/hub/catalogue-manager/product-families';
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: response.message
                    });
                });
            },
        },
        computed: {
            filtered() {
                return _.reject(this.families, filter => {
                    return filter.id == this.id;
                });
            }
        }
    }
</script>