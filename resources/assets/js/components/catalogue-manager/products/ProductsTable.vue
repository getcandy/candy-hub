<script>
    export default {
        data() {
            return {
                products: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                params: {
                    params: {
                        per_page: 20,
                        current_page: 1,
                        includes: 'channels,customer_groups,family'
                    }
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
        },
        methods: {
            loadProduct: id => {
                location.href = '/catalogue-manager/products/' + id;
            },
            loadProducts() {

                apiRequest.loadProducts(this.params, true)
                    .then(response => {
                        this.products = response;
                        //console.log(response);
                    });
            },
            selectAllClick() {

                this.selectAll = !this.selectAll;
            }
        }
    }
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#all-products" aria-controls="all-products" role="tab" data-toggle="tab">
                    All Products
                </a>
            </li>
            <li role="presentation">
                <a href="#shoes" aria-controls="shoes" role="tab" data-toggle="tab">
                    Shoes <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-products">
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
                                <input type="text" class="form-control" id="search" placeholder="Search">
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-2">

                            <button type="submit" class="btn btn-default btn-full" @click.prevent="loadProducts();">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>

                        </div>
                    </div>
                </form>

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
                            <th>
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
                            <th>Image</th>
                            <th>Product</th>
                            <th>Display</th>
                            <th>Purchasable</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="clickable" v-for="product in products">

                            <td>
                                <div class="checkbox">
                                    <input type="checkbox" :id="'prod' + product.id" :value="product.id" v-model="selected">
                                    <label :for="'prod' + product.id"><span class="check"></span></label>
                                </div>
                            </td>
                            <td @click="loadProduct(product.id)"><img src="/images/placeholder/product.jpg" :alt="product.name"></td>
                            <td @click="loadProduct(product.id)">{{ product.name }}</td>
                            <td @click="loadProduct(product.id)">{{ product.display }}</td>
                            <td @click="loadProduct(product.id)">{{ product.purchasable }}</td>
                            <td @click="loadProduct(product.id)">{{ product.group }}</td>
                        </tr>

                    </tbody>
                </table>

                <div class="text-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="First page" data-toggle="tooltip" data-placement="top"
                                   title="First page">
                                    <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                </a>
                            </li>
                            <li>
                                <a href="#" aria-label="Last page" data-toggle="tooltip" data-placement="top" title="Last page">
                                    <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</template>