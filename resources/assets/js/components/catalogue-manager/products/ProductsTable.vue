<script>
    import Flatify from '../../../classes/Flatify'
    import GroupService from '../../../services/Attributes/GroupService.js';

    export default {
        data() {
            return {
                loaded: false,
                savedSearches: [],
                products: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                editing: null,
                editingBackup: null,
                quickEditModal: false,
                filters: [],
                keywords: '',
                meta: [],
                params: {
                    type: 'product',
                    per_page: 25,
                    page: 1,
                    includes: 'channels,customer_groups,family,attribute_groups,variants,thumbnail.transforms'
                }
            }
        },
        watch: {
            selected: function(val) {
                this.checkedCount = val.length;
                this.selectAll = (val.length === this.products.length);
            },
            selectAll: function(val) {
                let selected = [];

                if (val) {
                    this.products.forEach(function (product) {
                        selected.push(product.id);
                    });
                }
                this.selected = selected;
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
                apiRequest.send('GET', 'products', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        this.params.total_pages = response.meta.pagination.total_pages;
                        this.loaded = true;
                    });
            },
            searchProducts() {
                this.loaded = false;
                apiRequest.send('GET', 'search', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        this.params.total_pages = response.meta.pagination.data.total_pages;
                        this.meta = response.meta;
                        this.loaded = true;
                    });
            },
            applySavedSearch(search) {
                if (search && search.payload.keywords) {
                    this.params['keywords'] = search.payload.keywords;
                    this.keywords = search.payload.keywords;
                }

                if (search && search.payload.filters) {
                    this.params['filters'] = search.payload.filters;
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
                this.params['filters'] = null;
                this.loadProducts();
            },
            saveSearch() {
                apiRequest.send('POST', '/saved-searches', {
                    type: 'product',
                    name: this.keywords,
                    keywords: this.keywords,
                    filters: this.filters
                }).then(response => {
                    this.savedSearches.push(response.data);
                });
            },
            productThumbnail(product) {
                if (product.thumbnail) {
                    return product.thumbnail.data.thumbnail;
                }
                return '/candy-hub/images/placeholder/no-image.png';
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
                if (product.variant_count == 1) {
                    return variants[0].inventory;
                }
                return 'Multiple';
            },
            getVisibilty(product, ref) {
                let groups = product[ref].data;
                let visible = [];
                groups.forEach(group => {
                    if (group.visible) {
                        visible.push(group.name);
                    }
                    if (group.published_at) {
                        let now = moment();
                        let publish_date = moment(group.published_at);
                        if (!publish_date.isAfter(now)) {
                            visible.push(group.name);
                        }
                    }
                });
                if (visible.length == groups.length) {
                    return 'All';
                }
                if (!visible.length) {
                    return 'None';
                }
                return visible.join(', ');
            },
            getAttributeGroups(product) {
                return GroupService.getLabel(
                    product.attribute_groups.data
                );
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

                variants.forEach(variant => {
                    apiRequest.send('put', '/products/variants/' + variant.id, variant)
                        .then(response => {
                        }).catch(response => {
                            CandyEvent.$emit('notification', {
                                level: 'error',
                                message: response.message
                            });
                            return false;
                        });
                });

                CandyEvent.$emit('notification', {
                    level: 'success',
                    message: 'Stock Updated'
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
            selectAllClick() {
                this.selectAll = !this.selectAll;
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
                <a href="#shoes" aria-controls="shoes" role="tab" data-toggle="tab" @click="applySavedSearch(search)">
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

                <!-- Filter List -->
                <!-- <div class="filters">
                    <div class="filter active">Visible on Storefront
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="filter active">Visible on Facebook
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div> -->

                <hr>

                <table class="table product-table">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="25%">Product</th>
                            <th width="10%">Stock</th>
                            <th width="15%">Display</th>
                            <th width="19%">Purchasable</th>
                            <th width="19%">Group</th>
                            <th  width="15%"></th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <template v-for="(product, index) in products">
                            <tr>

                                <td>
                                    <a :href="route('hub.products.edit', product.id)">
                                        <candy-thumbnail-loader :item="product"></candy-thumbnail-loader>
                                    </a>
                                </td>
                                <td>
                                    <a :href="route('hub.products.edit', product.id)">
                                        {{ product|attribute('name') }}
                                    </a>
                                </td>
                                <td>
                                    <template v-if="editing == index && product.variant_count == 1 && !this.quickEditModal">
                                        <input v-focus @keyup.enter="quickSave" class="form-control" v-model="product.variants.data[0].inventory" @blur="quickSave">
                                    </template>
                                    <template v-else>
                                        <a href="#" data-toggle="tooltip" v-on:click.prevent="quickEdit(index)" data-placement="top" title="Edit" class="editable-stock">
                                           {{ getStock(product) }}
                                        </a>
                                    </template>
                                </td>
                                <td>{{ getVisibilty(product, 'channels') }}</td>
                                <td>{{ getVisibilty(product, 'customer_groups') }}</td>
                                <td>
                                    {{ getAttributeGroups(product) }}
                                </td>
                                <td>
                                    <!-- <template v-if="editing == index">
                                        <button class="btn btn-danger btn-sm btn-action" @click="cancelQuickEdit" >
                                            <fa icon="times"></fa>
                                        </button>
                                        <button class="btn btn-success btn-sm btn-action" @click="quickSave">
                                            <fa icon="check"></fa>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button class="btn btn-default btn-sm btn-action" @click="quickEdit(index)">
                                            <fa icon="edit"></fa>
                                        </button>
                                        <a :href="'/catalogue-manager/products/' + product.id" class="btn btn-default btn-sm btn-action" >
                                            <fa icon="eye"></fa>
                                        </a>
                                    </template> -->
                                </td>
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