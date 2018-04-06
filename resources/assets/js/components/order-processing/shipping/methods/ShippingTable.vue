<script>
    export default {
        data() {
            return {
                loaded: false,
                methods: [],
                params: {
                    per_page: 50,
                    current_page: 1,
                    includes: 'prices.customer_groups,zones'
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
                    });
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadOrders();
            },
            loadMethod: function (id) {
                location.href = route('hub.shipping.edit', id);
            },
            getCustomerGroups(prices) {
                let groups = [];
                _.each(prices, price => {
                    _.each(price.customer_groups.data, group => {
                        if (group.visible && !groups.contains(group.name)) {
                            groups.push(group.name);
                        }
                    });
                });
                return groups;
                // return prices.customer_groups.data;
            },
            localisedPrice(price) {
                var currency = price.currency.data;
                // price.currency.data.format
                return currency.format.replace('{price}', price.rate.money(2, currency.thousand_point, currency.decimal_point));
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
            <div role="tabpanel" class="tab-pane active" id="all-methods">
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Prices</th>
                            <th>Zones</th>
                            <th>Customer Groups</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="method in methods" @click="loadMethod(method.id)">
                            <td>
                                {{ method|attribute('name') }}
                            </td>
                            <td>
                                <template v-if="method.prices.data.length">
                                    <template v-for="(price, index) in method.prices.data">
                                        <span v-html="localisedPrice(price)"></span><span v-if="index < method.prices.data.length - 1">, </span>
                                    </template>
                                </template>
                                <span class="text-info" v-else>No prices set</span>
                            </td>
                            <td>
                                <template v-if="method.zones.data.length">
                                    <template v-for="(zone, index) in method.zones.data">
                                        {{ zone.name }}<span v-if="index < method.zones.data.length - 1">,</span>
                                    </template>
                                </template>
                                <span class="text-info" v-else>No zones set</span>
                            </td>
                            <td>
                                <span v-for="(group, index) in getCustomerGroups(method.prices.data)">
                                    {{ group }}<span v-if="index != getCustomerGroups(method.prices.data).length - 1">, </span>
                                </span>
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