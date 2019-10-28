<template>
    <div>
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Attribute</label>
                            <select class="form-control" v-model="attribute">
                                <option value>Select an attribute</option>
                                <option v-for="attribute in attributes" :value="attribute" :key="attribute.id">{{ attribute.name.en }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Value</label>
                            <template v-if="loadingAttributeValues">
                                <div>
                                    <i class="fa fa-sync fa-spin"></i> Fetching values
                                </div>
                            </template>
                            <template v-else>
                                <select class="form-control" v-model="attributeValue">
                                    <option value>Select an attribute Value</option>
                                    <option v-for="(row, index) in attributeValues" :value="row.value" :key="index">{{ row.value ? row.value : 'EMPTY' }} ({{ row.count }})</option>
                                </select>
                                <p class="text-danger" v-if="tooManyUniqueValues">
                                    There are too many unique values, showing a small subset.
                                </p>
                            </template>
                        </div>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                        </tr>
                    </thead>
                    <tfoot v-if="searching">
                        <tr>
                            <td colspan="4">
                                 <i class="fa fa-sync fa-spin"></i> Loading
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td><a :href="route('hub.products.edit', product.sku)">{{ product.name }}</a></td>
                            <td>{{ product.sku }}</td>
                        </tr>
                    </tbody>
                </table>
                <candy-table-paginate v-if="products.length" :total="totalPages" :current="page" @change="changePage"></candy-table-paginate>
            </div>
        </div>
    </div>
</template>

<script>
    import UrlHelper from '../../../classes/UrlHelpers';
    export default {
        data() {
            return {
                products: [],
                page: 1,
                urlParams:  UrlHelper.params(),
                totalPages: 1,
                searching: false,
                tooManyUniqueValues: false,
                attribute: '',
                attributes: [],
                attributeValues: [],
                attributeValue: '',
                loadingAttributeValues: false,
            }
        },
        mounted() {
            this.loadAttributes(1);
            // if (this.attribute) {}
        },
        watch: {
            attribute(val) {
                if (val) {
                    UrlHelper.setParam('attribute', val.handle);
                    this.loadAttributeValues(1);
                } else {
                    UrlHelper.setParam('attribute', val);
                }

            },
            attributeValue(val) {
                UrlHelper.setParam('attribute_value', val);
                if (val) {
                    this.search();
                }
            }
        },
        methods: {
            loadAttributes(page) {
                apiRequest.send('get', '/attributes', [], {
                    page: page,
                })
                    .then(response => {
                        this.attributes = _.sortBy([...this.attributes, ...response.data], 'handle');
                        const attribute = this.urlParams.get('attribute') ? this.urlParams.get('attribute') : '';
                        this.attribute = _.find(this.attributes, (att) => {
                            return att.handle == attribute;
                        });

                        if (page < response.meta.pagination.total_pages) {
                            this.loadAttributes(page + 1);
                        }
                    });
            },
            loadAttributeValues(page) {
                this.tooManyUniqueValues = false;
                this.attributeValues = [];
                this.loadingAttributeValues = true;
                apiRequest.send('get', '/reports/attributes', [], {
                    attribute: this.attribute.handle,
                })
                    .then(response => {
                        this.attributeValues = _.sortBy([...this.attributeValues, ...response.data], 'handle');
                        this.attributeValue = this.urlParams.get('attribute_value') ? this.urlParams.get('attribute_value') : '';
                        // Only get the first 5 pages to avoid an crazy api spam
                        if (response.last_page > 5) {
                            this.tooManyUniqueValues = true;
                        }
                        if ((page <= 5) && (page < response.last_page)) {
                            this.loadAttributeValues(page + 1);
                        } else {
                            this.loadingAttributeValues = false;
                        }
                    });
            },
            changePage(page) {
                this.page = page;
                this.search();
            },
            search() {
                this.searching = true;
                this.products = [];
                apiRequest.send('get', '/reports/products/attributes', [], {
                    attribute: this.attribute.handle,
                    attribute_value: this.attributeValue,
                    page: this.page,
                })
                    .then(response => {
                        this.searching = false;
                        this.products = response.data;
                        this.page = response.current_page;
                        this.totalPages = response.last_page;
                    });
            }
        }
    }
</script>

<style scoped>

</style>