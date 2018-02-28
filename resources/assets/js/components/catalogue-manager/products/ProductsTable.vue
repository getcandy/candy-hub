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
                meta: [],
                params: {
                    type: 'product',
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
                location.href = '/hub/catalogue-manager/products/' + id;
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
                        this.params.total_pages = response.meta.pagination.total_pages;
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
                return '/hub/images/placeholder/no-image.png';
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.params['keywords'] = this.keywords;

                    if (this.keywords) {
                        this.searchProducts();
                    } else {
                        this.loadProducts();
                    }
                }, 500
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
                this.search();
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
                <template v-if="meta.suggestions">
                    <template v-for="(options, field) in meta.suggestions">
                        <template v-for="option in options">
                            Did you mean <a href="#" @click="suggest(option.text)"><span v-html="option.highlighted"></span></a>?
                        </template>
                    </template>
                    <hr>
                </template>
                <hr>
                <table class="table table-striped product-table">
                    <thead>
                        <tr>
                            <th width="10%">Image</th>
                            <th width="25%">Product</th>
                            <th width="19%">Display</th>
                            <th width="19%">Purchasable</th>
                            <th width="19%">Group</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="product in products">
                            <td @click="loadProduct(product.id)">
                                <candy-thumbnail-loader :item="product"></candy-thumbnail-loader>
                            </td>
                            <td @click="loadProduct(product.id)">{{ product|attribute('name') }}</td>
                            <td @click="loadProduct(product.id)">{{ getVisibilty(product, 'channels') }}</td>
                            <td @click="loadProduct(product.id)">{{ getVisibilty(product, 'customer_groups') }}</td>
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

        </div>
    </div>
</template>