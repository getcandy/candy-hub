<template>
    <div>
        <button type="button" class="btn btn-primary" @click="show = true">{{ buttonText }}</button>

        <candy-modal title="Add product associations" v-show="show" @closed="show = false">
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
                                    <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody class="list">
                    <tr v-for="product in products" :key="product.id">
                        <td width="10%">
                        <!-- <img :src="productThumbnail(product)" :alt="product|attribute('name')" class="img-sm"> -->
                        </td>
                        <td class="name" width="40%">{{ product|attribute('name') }}</td>
                        <td align="right">
                            <button @click="attach(product)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(product)">
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
                <button class="btn btn-primary" @click="emit">{{ buttonConfirm }}</button>
            </template>
        </candy-modal>
    </div>
</template>

<script>
    export default {
        props: {
            buttonText: {
                type: String,
                default: 'Browse products',
            },
            buttonConfirm: {
                type: String,
                default: 'Browse products',
            },
            current: {
                type: Array,
                default() {
                    return [];
                }
            },
            perPage: {
                type: String|Number,
                default: 12,
            },
        },
        data() {
            return {
                show: false,
                loading: false,
                products: [],
                selected: [],
                requestParams: {
                    per_page: this.perPage,
                    current_page: 1,
                    keywords: '',
                    type: 'product'
                }
            }
        },
        mounted() {
            this.selected = this.current;
        },
        computed: {
            ids() {
                return _.map(this.selected, item => {
                    return item.id;
                });
            }
        },
        methods: {
            emit() {
                this.$emit('saved', this.selected);
                this.show = false;
            },
            alreadyLinked(product) {
                const result = _.find(this.selected, item => {
                    return item.id == product.id;
                });
                return result;
            },
            attach(product) {
                this.selected.push(product);
            },
            detatch(product) {
                this.selected.splice(this.selected.indexOf(product), 1);
            },
            search() {
                this.loading = true;
                this.products = [];
                this.requestParams.keywords = this.keywords;
                apiRequest.send('GET', 'search', {}, this.requestParams).then(response => {
                    this.products = response.data;
                    this.requestParams.total_pages = response.meta.pagination.data.total_pages;
                    this.meta = response.meta;
                    this.loading = false;
                });
            },
            changePage(page) {
                this.requestParams.current_page = page;
                this.search();
            },
            updateKeywords: _.debounce(function (e) {
                this.keywords = e.target.value;
                this.requestParams.current_page = 1;
                this.search();
            }, 500)
        }
    }
</script>

<style scoped>

</style>