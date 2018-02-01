<script>
    export default {
        data() {
            return {
                products: [],
                loading: false,
                unsearched: true,
                payload: {},
                keywords: '',
                selected: [],
                params: {
                    type: 'product',
                    per_page: 25,
                    current_page: 1
                }
            }
        },
        props: {
            item: {
                type: String
            }
        },
        mounted() {
            this.$set(this.item, 'eligibles', this.payload);
            /*
            if (!this.payload.value) {
                this.$set(this.payload, 'value', []);
            } else {
                this.getSelectedModels();
            }*/
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
            sync() {
                var mappedItems = _.map(this.selected, item => {
                    return item.id;
                });
                this.$set(this.item, 'eligibles', mappedItems);
            },
            remove(id) {
                this.selected.splice(this.selected.indexOf(id), 1);
            },
            unselect(index) {
                this.selected.splice(index, 1);
                this.sync();
            },
            getSelectedModels()
            {
                apiRequest.send('GET', 'products', [], {
                    'ids' : this.payload.value
                })
                .then(response => {
                    this.selected = response.data;
                });
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
        <h5>Product in list</h5>
        <div class="filters">
            <div class="filter active" v-for="(product, index) in selected">
                {{ product|attribute('name') }}
                <button class="delete" @click="unselect(index)"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
        <hr>
        <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords" autocomplete="off">
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
                            <input type="checkbox" v-model="selected" @change="sync" :value="product">
                            {{ product|attribute('name') }}
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
