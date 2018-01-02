<script>
    export default {
        data() {
            return {
                products: [],
                loading: false,
                unsearched: true,
                selected: [],
                keywords: '',
                params: {
                    type: 'product',
                    per_page: 25,
                    current_page: 1
                }
            }
        },
        props: {
            criteria: {
                type: Array
            }
        },
        computed: {
            payload() {
                return _.map(this.selected, function (item) {
                    return item.id;
                });
            }
        },
        watch: {
            selected: function (values) {
        // LOOK AT this.$emit('val') on the v-model component to get it updated...
                
            }
        },
        methods: {
            searchProducts() {
                this.loading = true;
                apiRequest.send('GET', 'search', [], this.params)
                    .then(response => {
                        this.products = response.data;
                        this.params.total_pages = response.meta.pagination.total_pages;
                        this.meta = response.meta;
                        this.loading = false;
                    });
            },
            remove(id) {
                this.criteria.products.splice(this.criteria.products.indexOf(id), 1);
            },
            unselect(index) {
                this.selected.splice(index, 1);
            },
            search: _.debounce(function (){
                    this.unsearched = false;
                    this.loading = true;
                    this.products = [];
                    this.params['keywords'] = this.keywords;

                    if (this.keywords) {
                        this.searchProducts();
                    }
                }, 500
            )
        }
    }
</script>


<template>
    <div>
        {{ criteria }}
        <h5>Product in list</h5>
        <div class="filters">
            <div class="filter active" v-for="(product, index) in selected">
                {{ product|attribute('name') }}
                <button class="delete" @click="unselect(index)"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
        <hr>
        <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                </tr>
            </thead>
            <tfoot>
                <tr v-if="unsearched">
                    <td colspan="2">Search products above</td>
                </tr>
                <tr v-if="loading">
                    <td colspan="2">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>
                        <label>
                            <input type="checkbox" v-model="selected" :value="product">
                            {{ product|attribute('name') }}
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
