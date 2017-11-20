<script>
    import Flatify from '../../../classes/Flatify'

    export default {
        data() {
            return {
                loaded: false,
                savedSearches: [],
                products: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                filters: [],
                keywords: '',
                params: {
                    per_page: 25,
                    current_page: 1,
                    includes: 'channels,customer_groups,family,attribute_groups'
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
        methods: {
            isActive(terms) {
                if (terms == 'all' && !this.keywords) {
                    return true;
                } else if (terms.payload && this.keywords == terms.payload.keywords) {
                    return true;
                }
                return false;
            },
            loadProduct: function (id) {
                location.href = '/catalogue-manager/products/' + id;
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
                apiRequest.send('GET', 'search/products', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        this.params.total_pages = response.meta.pagination.total_pages;
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
                return '/images/placeholder/no-image.svg';
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.params['keywords'] = this.keywords;

                    if (this.keywords) {
                        this.searchProducts();
                    } else {
                        this.loadProducts();
                    }
                }, 1000
            ),
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
                let groups = product.attribute_groups.data,
                    visible = [];

                groups.forEach(group => {
                    visible.push(group.name);
                });

                // if (visible.length == groups.length) {
                //     return 'All';
                // }
                if (!visible.length) {
                    return 'None';
                }

                return visible.join(', ');
            },
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadProducts();
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
                <form>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">

                            <button type="button" class="btn btn-default btn-full btn-pop-over">
                                Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i>
                            </button>

                            <!-- Filter Pop Over -->
                            <div class="pop-over">
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
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-8">

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

                <!-- Filter List -->
                <div class="filters">
                    <div class="filter active">Visible on Storefront
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="filter active">Visible on Facebook
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>

                <hr>

                <table class="table table-striped product-table">
                    <thead>
                        <tr>
                            <th width="6%">
                                <div class="checkbox bulk-options" :class="{'active': (selectAll || checkedCount > 0)}">
                                    <input v-model="selectAll" type="checkbox" class="select-all">
                                    <label @click="selectAllClick"><span class="check"></span></label>
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>

                                    <div class="bulk-actions">
                                        <div class="border-inner">
                                            {{ checkedCount }} product selected
                                            <a href="#" class="btn btn-outline btn-sm">Edit</a>
                                            <a href="#" class="btn btn-outline btn-sm">Publish</a>
                                            <a href="#" class="btn btn-outline btn-sm">Hide</a>
                                            <a href="#" class="btn btn-outline btn-sm">Delete</a>
                                        </div>
                                        <div v-if="checkedCount == products.length" class="all-selected">
                                            <em>All products on this page are selected</em>
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th width="10%">Image</th>
                            <th width="25%">Product</th>
                            <th width="19%">Display</th>
                            <th width="19%">Purchasable</th>
                            <th width="19%">Group</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="product in products">
                            <td>
                                <div class="checkbox">
                                    <input type="checkbox" :id="'prod' + product.id" :value="product.id" v-model="selected">
                                    <label :for="'prod' + product.id"><span class="check"></span></label>
                                </div>
                            </td>
                            <td @click="loadProduct(product.id)">
                                <candy-thumbnail-loader :item="product"></candy-thumbnail-loader>
                            </td>
                            <td @click="loadProduct(product.id)">{{ product|attribute('name') }}</td>
                            <td @click="loadProduct(product.id)">{{ getVisibilty(product, 'customer_groups') }}</td>
                            <td @click="loadProduct(product.id)">{{ getVisibilty(product, 'channels') }}</td>
                            <td @click="loadProduct(product.id)">{{ getAttributeGroups(product) }}</td>

                        </tr>
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
                </table>

                <div class="text-center" v-if="loaded">
                    <candy-table-paginate :pagination="params" @change="changePage"></candy-table-paginate>
                </div>
            </div>

        </div>
    </div>
</template>