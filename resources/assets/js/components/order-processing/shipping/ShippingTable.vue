<script>
    export default {
        data() {
            return {
                loaded: false,
                methods: [],
                params: {
                    per_page: 50,
                    current_page: 1,
                    includes: 'prices,zones'
                },
                pagination: {}
            }
        },
        mounted() {
            this.loadMethods();
            CandyEvent.$on('shipping-method-added', product => {
                this.loadMethods();
            });
        },
        methods: {
            loadMethods() {
                apiRequest.send('get', '/shipping', {}, this.params)
                    .then(response => {
                        this.methods = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                        // apiRequest.send('GET', 'currencies').then(response => {
                        //     this.currencies = response.data;
                        //     this.loaded = true;
                        // });
                    });
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadOrders();
            },
            loadMethod: function (id) {
                location.href = '/order-processing/shipping/' + id;
            },
            localisedPrice(amount, currency) {
                var currency = _.find(this.currencies, item => {
                    return item.code == currency;
                });
                return currency.format.replace('{price}', amount.money(2, currency.thousand_point, currency.decimal_point));
            }
        }
    }
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#shipping-methods" aria-controls="shipping-methods" role="tab" data-toggle="tab">
                    Shipping Methods
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-collections">
                <!-- Search Form -->
                <form>
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-8">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                    <fa icon="search" />
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Prices</th>
                            <th>Zones</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="method in methods">
                            <td @click="loadMethod(method.id)">
                                {{ method|attribute('name') }}
                            </td>
                            <td>
                                <template v-if="method.prices.data.length">
                                    <template v-for="price, index in method.prices.data">
                                        {{ price.rate }}<span v-if="index < method.prices.data.length - 1">,</span>
                                    </template>
                                </template>
                                <span class="text-info" v-else>No prices set</span>
                            </td>
                            <td>
                                <template v-if="method.zones.data.length">
                                    <template v-for="zone in method.zones.data">
                                        {{ zone.name }}<span v-if="index < method.zones.data.length - 1">,</span>
                                    </template>
                                </template>
                                <span class="text-info" v-else>No zones set</span>
                            </td>
                        </tr>


                    </tbody>
                    <tfoot class="text-center" v-else>
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><fa icon="spinner" size="3x" spin /></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center">
                    <candy-table-paginate :pagination="pagination" @change="changePage"></candy-table-paginate>
                </div>
            </div>

        </div>
    </div>
</template>