<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Products</h4>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-right">
                        <button type="button" class="btn btn-primary" @click="showModal = true">Add a Product</button>
                    </div>
                </div>

                <hr>
                <table class="table table-striped sortable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, index) in items" :key="index">
                            <td>{{ product|attribute('name') }}</td>
                            <td align="right">
                                <button class="btn btn-sm btn-default btn-action" @click="remove(index)">
                                    <i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--
            Modal
        -->
        <candy-modal title="Add product associations" v-show="showModal" @closed="showModal = false">
            <div slot="body">
            <div class="form-group">
                <label class="sr-only">Search</label>
                <input type="text" class="form-control search" placeholder="Search Products" v-on:input="updateKeywords">
            </div>
            <hr>
            <table class="table association-table">
                <thead>
                <tr>
                    <th> </th>
                    <th>Name</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot v-if="loading" class="text-center">
                    <tr>
                        <td colspan="25" style="padding:40px;">
                            <div class="loading">
                                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody class="list">
                <tr v-for="product in results" :key="product.id">
                    <td width="10%">
                    <!-- <img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"> -->
                    </td>
                    <td class="name" width="40%">{{ product|attribute('name') }}</td>
                    <td align="right">
                        <button @click="assign(product)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(product)">
                            <fa icon="plus"></fa>
                        </button>
                        <button @click="detatch(product)" class="btn btn-sm btn-default btn-action" v-else>
                            <fa icon="trash"></fa>
                        </button>
                    </td>
                </tr>
                <!-- <tr v-for="product in products">
                    <td width="20%"><img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"></td>
                    <td class="name" width="60%">{{ product|attribute('name') }}</td>
                    <td>
                    <select class="form-control selectize" v-model="loaded[product.id].type">
                        <option v-for="group in groups" :value="group.handle">
                        {{ group.name }}
                        </option>
                    </select>
                    </td>

                    <td align="right">
                    <div class="checkbox">
                        <input :id="product.id" type="checkbox" :value="product.id" v-model="loaded[product.id].selected">
                        <label :for="product.id"><span class="check"></span></label>
                    </div>
                    </td>
                </tr> -->
                <tr v-if="!loading && !products">
                    <td colspan="25">
                    <div class="alert alert-info">
                        Start typing to see products
                    </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="text-center">
                <candy-table-paginate :pagination="requestParams" @change="changePage" v-if="!loading"></candy-table-paginate>
            </div>
            </div>
            <template slot="footer">
                <button class="btn btn-primary" @click="save()">Associate products</button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            collectionId: {
                type: String,
                required: true,
            },
            products: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        data() {
            return {
                items: [],
                results: [],
                showModal: false,
                loading: false,
                keywords: null,
                requestParams: {
                    per_page: 12,
                    current_page: 1,
                    keywords: '',
                    type: 'product'
                }
            }
        },
        computed: {
            ids() {
                return _.map(this.items, item => {
                    return item.id;
                });
            }
        },
        mounted() {
            this.items = this.products;
        },
        methods: {
            alreadyLinked(product) {
                return this.ids.contains(product.id);
            },
            assign(product) {
                this.items.push(product);
            },
            save() {
                this.showModal = false;
                this.update();
            },
            update() {
                const payload = _.map(this.items, item => {
                    return item.id;
                });

                apiRequest.send('PUT', '/collections/' + this.collectionId + '/products', {
                    products: payload
                })
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                });
            },
            remove(index) {
                this.items.splice(index, 1);
                this.update();
            },
            getResults(keywords) {
                this.requestParams.keywords = this.keywords;
                apiRequest.send('GET', 'search', {}, this.requestParams).then(response => {
                    this.results = response.data;
                    this.requestParams.total_pages = response.meta.pagination.data.total_pages;
                    this.meta = response.meta;
                    this.loading = false;
                });
            },
            changePage(page) {
                this.results = [];
                this.loading = true;
                this.requestParams.current_page = page;
                this.getResults(this.keywords);
            },
            updateKeywords: _.debounce(function (e) {
                this.results = null;
                this.loading = true;
                this.keywords = e.target.value;
                this.requestParams.current_page = 1;
                this.getResults(e.target.value);
            }, 500)
        }
    }
</script>

<style scoped>

</style>