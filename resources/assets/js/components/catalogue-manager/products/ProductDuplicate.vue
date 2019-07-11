<script>
    export default {
        props: {
            productId: {
                type: String,
                default: null,
            },
        },
        data() {
            return {
                show: false,
                fetching: true,
                product: {},
                skus: [],
                slugs: [],
                duplicates: [],
                processing: false,
                errors: [],
            }
        },
        mounted() {
            this.fetch(true);
        },
        computed: {
            variants() {
                return this.product.variants ? this.product.variants.data : null;
            },
            routes() {
                return this.product.routes ? this.product.routes.data : null;
            }
        },
        methods: {
            fetch(showFetch) {
                this.skus = [];
                this.slugs = [];

                this.fetching = showFetch;
                apiRequest.send('get', '/products/' + this.productId, {}, {
                    includes: [
                        'variants',
                        'routes',
                    ]
                }).then(response => {
                    this.fetching = false;
                    this.product = response.data;
                    _.each(this.variants, v => {
                        this.skus.push({
                            current: v.sku,
                            new: null,
                        });
                    })
                    _.each(this.routes, r => {
                        this.slugs.push({
                            current: r.slug,
                            new: r.slug,
                        });
                    })
                });
            },
            save(redirect) {
                this.processing = true;
                this.errors = [];

                apiRequest.send('POST', `products/${this.productId}/duplicate`, {
                    routes: this.slugs,
                    skus: this.skus,
                }).then(response => {
                    if (!redirect) {
                        this.fetch(false);
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: 'Duplicate Created',
                        });
                        return;
                    }
                    window.location = route('hub.products.edit', response.data.id);
                }).catch(error => {
                    this.errors = error.response.data;
                }).finally(() => {
                    this.processing = false;
                })


            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-default white" @click="show = true"><fa icon="copy" /></button>
        <candy-modal title="Duplicate Product" v-show="show" size="modal-md" @closed="show = false">
            <div slot="body">
                <template v-if="fetching">
                    <fa icon="sync" spin/> Loading
                </template>
                <template v-else>
                    <p>Fill in the fields below to create a duplicate product. You will be able to change the other fields once it's been created</p>
                    <p class="text-info">Any duplicated products are initially set as inactive</p>
                    <div class="form-group">
                        <label>SKU<span v-if="skus.length > 1">'s</span></label>
                        <template v-for="(sku, index) in skus" >
                            <input v-model="sku.new" :key="sku.current" class="form-control" :placeholder="sku.current" />
                            <template v-if="errors[`skus.${index}.new`]">
                                <br><p class="text-danger" v-for="(error, index) in errors[`skus.${index}.new`]">{{ error }}</p>
                            </template>
                        </template>

                    </div>

                    <div class="form-group">
                        <label>URL<span v-if="routes.length > 1">'s</span></label>
                        <template v-for="(slug, index) in slugs">
                            <input :key="slug.current" v-model="slug.new" class="form-control" :placeholder="slug.current" />
                            <template v-if="errors[`routes.${index}.new`]">
                                <br><p class="text-danger" v-for="(error, index) in errors[`routes.${index}.new`]">{{ error }}</p>
                            </template>
                        </template>
                    </div>

                </template>
            </div>
            <template slot="footer">
                <template v-if="!processing">
                    <button type="button" class="btn btn-primary" @click="save(true)">Create & edit duplicate</button>
                    <button type="button" class="btn btn-primary" @click="save(false)">Create & duplicate again</button>
                </template>
                <template v-else>
                    <button type="button" class="btn btn-default" disabled>
                        <fa icon="sync" spin /> Processing
                    </button>

                </template>
            </template>
        </candy-modal>
    </div>
</template>
