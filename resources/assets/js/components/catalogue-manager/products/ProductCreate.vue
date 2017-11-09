<script>
    export default {
        data() {
            return {
                request : apiRequest,
                createProduct: false,
                product: this.baseProduct(),
                families: []
            }
        },
        mounted() {
            this.request.send('GET', 'product-families')
                .then(response => {
                    response.data.forEach((family, index) => {
                        if (index == 0) {
                            this.product.family_id = family.id;
                        }
                        console.log(family);
                        this.families.push({
                            id: family.id,
                            label: family.attribute_data.name[locale.current()]
                        });
                    });
                });
        },
        computed: {
            productName: {
                get() {
                    return this.product.name[locale.current()];
                },
                set(value) {
                    this.product.name[locale.current()] = value;
                }
            },
            sluggedName() {
                return this.product.name[locale.current()].slugify();
            },
            productUrl: {
                get() {
                    return this.product.url.slugify();
                }
            }
        },
        methods: {
            save() {
                this.product.url = this.product.url.slugify();
                this.request.send('post', '/products', this.product)
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                    this.createProduct = false;
                    this.product = this.baseProduct();
                    CandyEvent.$emit('product-added', response.data);
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            },
            baseProduct() {
                return {
                    name: {
                        [locale.current()] : ''
                    },
                    family_id: null,
                    sku: '',
                    price: '',
                    url: '',
                    stock: 1
                }
            }
        }
    }
</script>

<template>
    <div>
        <button class="btn btn-success" @click="createProduct = true"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Add Product</button>
        <candy-modal title="Create Product" v-show="createProduct" size="modal-md" @closed="createProduct = false">
            <div slot="body">
                <div class="form-group">
                    <label for="name">Product name</label>
                    <input type="text" class="form-control" id="name" v-model="productName" @input="request.clearError('name')">
                    <span class="text-danger" v-if="request.getError('name')" v-text="request.getError('name')"></span>
                </div>
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control" id="sku" v-model="product.sku" @input="request.clearError('sku')">
                    <span class="text-danger" v-if="request.getError('sku')" v-text="request.getError('sku')"></span>
                </div>
                <div class="form-group">
                    <label for="sku">Price</label>
                    <input type="text" class="form-control" id="price" v-model="product.price" @input="request.clearError('price')">
                    <span class="text-danger" v-if="request.getError('price')" v-text="request.getError('price')"></span>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" v-model="product.stock" @input="request.clearError('stock')">
                    <span class="text-danger" v-if="request.getError('stock')" v-text="request.getError('stock')"></span>
                </div>
                <div class="form-group">
                    <label for="redirectURL">Enter the slug product.</label>
                    <input type="text" id="redirectURL" class="form-control" v-model="product.url">
                    <span class="text-info" v-if="product.url">Your url will be sanitized to: <code>{{ productUrl }}</code></span>
                    <span class="text-danger" v-if="request.getError('url')" v-text="request.getError('url')"></span>
                </div>


                <div class="form-group">
                    <label for="redirectURL">Product family</label>
                    <select name="" id="" class="form-control" v-model="product.family_id">
                        <option v-for="family in families" :value="family.id">{{ family.label }}</option>
                    </select>
                    <span class="text-danger" v-if="request.getError('slug')" v-text="request.getError('slug')"></span>
                </div>

            </div>
            <template slot="footer">
                <button type="button" class="btn btn-primary" @click="save">Create product</button>
            </template>
        </candy-modal>
    </div>
</template>
