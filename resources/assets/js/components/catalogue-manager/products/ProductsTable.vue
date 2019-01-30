<script>
    import Flatify from '../../../classes/Flatify'
    import HasGroups from '../../../mixins/HasGroups.js';
    import HasAttributes from '../../../mixins/HasAttributes.js';

    export default {
        mixins: [
            HasGroups,
            HasAttributes,
        ],
        data() {
            return {
                loaded: false,
                savedSearches: [],
                products: [],
                editing: null,
                editingBackup: null,
                quickEditModal: false,
                keywords: '',
                meta: [],
                params: {
                    type: 'product',
                    fields: 'name',
                    per_page: 25,
                    page: 1,
                    includes: 'channels,customerGroups,family,variants,assets.transforms'
                }
            }
        },

        mounted() {
            this.loadProducts();
            CandyEvent.$on('product-added', product => {
                this.loadProducts();
            });

            apiRequest.send('GET', '/saved-searches/product')
                .then(response => {
                    this.savedSearches = response.data;
                });
        },
        computed: {
            editableVariants() {
                if (!this.editing) {
                    return [];
                }
                return this.products[this.editing].variants.data;
            }
        },
        methods: {
            route(path, param1) {
                return route(path, param1);
            },
            suggest(term) {
                this.keywords = term;
                this.search();
            },
            isActive(terms) {
                if (terms == 'all' && !this.keywords) {
                    return true;
                } else if (terms.payload && this.keywords == terms.payload.keywords) {
                    return true;
                }
                return false;
            },
            loadProduct: function (id) {
                location.href = route('hub.products.edit', id);
            },
            loadProducts() {
                this.loaded = false;
                this.params['includes'] = 'channels,customerGroups,family,variants,assets.transforms';
                apiRequest.send('GET', 'products', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        // this.params.total_pages = response.meta.last_page;
                        this.loaded = true;
                    });
            },
            searchProducts() {
                this.loaded = false;
                this.params['includes'] = 'channels,customer_groups,family,variants,assets.transforms';
                apiRequest.send('GET', 'search', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        this.params.total_pages = response.meta.last_page;
                        this.meta = response.meta;
                        this.loaded = true;
                    });
            },
            applySavedSearch(search) {
                if (search && search.payload.keywords) {
                    this.params['keywords'] = search.payload.keywords;
                    this.keywords = search.payload.keywords;
                }
                this.searchProducts();
            },
            deleteSaved(index) {
                let search = this.savedSearches[index];
                this.savedSearches.splice(index, 1);
                apiRequest.send('DELETE', 'saved-searches/' + search.id);

                if (this.keywords == search.payload.keywords) {
                    this.keywords = '';
                }
            },
            resetSearch() {
                this.params['keywords'] = null;
                this.keywords = '';
                this.loadProducts();
            },
            saveSearch() {
                apiRequest.send('POST', '/saved-searches', {
                    type: 'product',
                    name: this.keywords,
                    keywords: this.keywords
                }).then(response => {
                    this.savedSearches.push(response.data);
                });
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.editing = null;
                    this.params['keywords'] = this.keywords;

                    if (this.keywords) {
                        this.searchProducts();
                    } else {
                        this.loadProducts();
                    }
                }, 500
            ),
            getStock(product) {
                var variants = product.variants.data;
                if (variants.length == 1) {
                    return variants[0].inventory;
                }
                return 'Multiple';
            },
            quickEdit(index) {
                this.editing = index;
                this.editingBackup = JSON.parse(JSON.stringify(this.products[index]));
                if (this.editableVariants.length > 1) {
                    this.quickEditModal = true;
                }
            },
            quickSave() {
                var product = this.products[this.editing];
                var variants = product.variants.data;

                variants.forEach((variant, index) => {
                    // Only save if it has changed.
                    if (JSON.stringify(variant) == JSON.stringify(this.editingBackup.variants.data[index])) {
                        return;
                    }
                    apiRequest.send('put', '/products/variants/' + variant.id + '/inventory', variant)
                        .then(response => {
                            CandyEvent.$emit('notification', {
                                level: 'success',
                                message: 'Stock Updated'
                            });
                        }).catch(response => {
                            CandyEvent.$emit('notification', {
                                level: 'error',
                                message: response.message
                            });
                            return false;
                        });
                });

                this.editingBackup = null;
                this.editing = null;
                this.quickEditModal = false;
            },
            cancelQuickEdit() {
                this.products[this.editing] = this.editingBackup;
                this.editingBackup = null;
                this.editing = null;
            },
            changePage(page) {
                this.loaded = false;
                this.params.page = page;
                this.search();
            }
        },
        directives: {
            focus: {
                // directive definition
                inserted: function (el) {
                    el.focus()
                }
            }
        }

    }
</script>

<template>
    <div>
        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" :class="{'active' : isActive('all')}">
                <a href="#all-products" aria-controls="all-products" role="tab" data-toggle="tab" @click="resetSearch()">
                    All Products
                </a>
            </li>
            <li role="presentation" v-for="(search, index) in savedSearches" :key="search.id" :class="{'active' : isActive(search)}">
                <a href="#"  role="tab" data-toggle="tab" @click="applySavedSearch(search)">
                    {{ search.name }} <i class="fa fa-times" aria-hidden="true" @click="deleteSaved(index)"></i>
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-products">

                <!-- Search Form -->
                <form v-on:submit.prevent>
                    <div class="row">
                        <!-- <div class="col-xs-12 col-md-2"> -->
                            <!-- <candy-disabled>
                                <button type="button" class="btn btn-default btn-full btn-pop-over">
                                    Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i>
                                </button>
                            </candy-disabled> -->

                            <!-- Filter Pop Over -->
                            <!-- <div class="pop-over">
                                <form>
                                    <label>Show all products where:</label>
                                    <div class="form-group">
                                        <select class="form-control selectpicker">
                                            <option>Display</option>
                                        </select>
                                    </div>
                                    <span class="form-link">
                                        is
                                    </span>
                                    <div class="form-group">
                                        <select class="form-control selectpicker">
                                            <option>Visible on Storefront</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-default">Add filter</button>
                                </form>
                            </div> -->

                        <!-- </div> -->
                        <div class="form-group col-xs-12 col-md-10">

                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-2">

                            <button type="button" class="btn btn-default btn-full" @click="saveSearch()">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>

                        </div>
                    </div>
                </form>
                <template v-if="meta.suggestions">
                    <template v-for="(options, field) in meta.suggestions">
                        <template v-for="option in options">
                            Did you mean <a href="#" @click="suggest(option.text)"><span v-html="option.highlighted"></span></a>?
                        </template>
                    </template>
                    <hr>
                </template>
                <hr>

                <table class="table product-table">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="25%">Product</th>
                            <th width="10%">Stock</th>
                            <th width="15%">Channels</th>
                            <th width="19%">Customer Groups</th>
                            <th width="19%">Purchasable</th>
                            <!-- <th width="19%">Group</th> -->
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <template v-for="(product, index) in products">
                            <tr :key="product.id">
                                <td>
                                    <a :href="route('hub.products.edit', product.id)">
                                        <candy-thumbnail-loader :item="product"></candy-thumbnail-loader>
                                    </a>
                                </td>
                                <td>
                                    <a :href="route('hub.products.edit', product.id)">
                                        <!-- legacy support, needs removing when search goes to eloquent resources -->
                                        <template v-if="product.name">
                                            {{ product.name }}
                                        </template>
                                        <template v-else>
                                            {{ product|attribute('name') }}
                                        </template>
                                    </a>
                                </td>
                                <td>
                                    <template v-if="editing == index && product.variants.data.length == 1 && !this.quickEditModal">
                                        <input v-focus @keyup.enter="quickSave" class="form-control" v-model="product.variants.data[0].inventory" @blur="quickSave">
                                    </template>
                                    <template v-else>
                                        <a href="#" data-toggle="tooltip" v-on:click.prevent="quickEdit(index)" data-placement="top" title="Edit" class="editable-stock">
                                           {{ getStock(product) }}
                                        </a>
                                    </template>
                                </td>

                                <td>{{ visibility(product, 'channels') }}</td>
                                <td>{{ visibility(product, 'customer_groups') }}</td>
                                <td>{{ purchasable(product, 'customer_groups') }}</td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="text-center" v-else>
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tfoot v-if="loaded && !products.length">
                        <tr>
                            <td colspan="25" class="text-muted">
                                No Products Found
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center" v-if="loaded && products.length">
                    <candy-table-paginate :pagination="params" @change="changePage"></candy-table-paginate>
                </div>
            </div>
            <candy-modal title="Edit Stock" v-show="quickEditModal" size="modal-md" @closed="quickEditModal = false">
                <div slot="body">
                    <div v-for="(variant, vIndex) in editableVariants" class="form-group">
                        <label>{{ variant.sku }}</label>
                       <input class="form-control" v-model="variant.inventory">
                    </div>
                </div>
                <template slot="footer">
                    <button type="button" class="btn btn-primary" @click="quickSave">Save Stock</button>
                </template>
            </candy-modal>

        </div>
    </div>
</template>

<style lang="scss" scoped>
    .editable-stock {
        padding:2px 4px;
        position: relative;
        border:1px dashed transparent;
        color:#333;
        &:hover {
            text-decoration: none;
            background-color:#f5f5f5;
            border-color: #BEBEBE;
        }
    }
</style>