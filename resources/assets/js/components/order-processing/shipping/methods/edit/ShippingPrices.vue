<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data() {
            return {
                editable: {
                    currency_id: ''
                },
                prices: [],
                current: {},
                currencies: [],
                defaultCurrency: '',
                carbon: null
            }
        },
        props: {
            method: {
                type: Object,
                required: true
            }
        },
        mounted() {
            apiRequest.send('get', 'currencies').then(response => {
                this.currencies = response.data;
                this.defaultCurrency = _.find(this.currencies, item => {
                    return item.default;
                }).id;
            });
            this.prices = this.method.prices.data;
            _.each(this.prices, price => {
                this.$set(price, 'currency_id', price.currency.data.id);
            });
        },
        computed: {
            modalTitle() {
                return this.current.id ? 'Edit Price' : 'Create Price';
            },
            editing() {
                return Object.keys(this.current).length != 0;
            }
        },
        methods: {
            symbol(price) {
                if (!this.currency(price)) {
                    return '';
                }
                return this.currency(price).format.replace('{price}', '');
            },
            currency(price) {
                if (!this.currencies.length) {
                    return '';
                }
                return _.find(this.currencies, item => {
                    return price.currency_id == item.id;
                })
            },
            savePrice(price) {
                if (this.current.id) {
                    this.update();
                } else {
                    this.create();
                }
            },
            currencySelect() {
                var options = _.map(this.currencies, item => {
                    return {
                        label: item.name,
                        value: item.id
                    }
                });
                return options;
            },
            destroy(price) {
                this.prices.splice(this.prices.indexOf(price), 1);
                apiRequest.send('delete', '/shipping/prices/' + price.id).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Price deleted'
                    });
                });
            },
            edit(price) {
                this.current = JSON.parse(JSON.stringify(price));
                this.current.currency_id = this.current.currency.data.id;
            },
            add() {
                apiRequest.send('get', '/customers/groups').then(response => {

                    this.current = {
                        currency_id: this.defaultCurrency,
                        rate: '',
                        fixed: '',
                        min_basket: 0,
                        min_weight: 0,
                        weight_unit: 'kg',
                        min_height: 0,
                        height_unit: 'cm',
                        min_width: 0,
                        width_unit: 'cm',
                        min_depth: 0,
                        depth_unit: 'cm',
                        min_volume: 0,
                        volume_unit: 'l',
                        customer_groups: response
                    };
                });
            },
            create() {
                apiRequest.send('post', '/shipping/' + this.method.id + '/prices', this.current)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('shipping-prices-updated');
                        this.current = {};
                    }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
            },
            update() {
                apiRequest.send('put', 'shipping/prices/' + this.current.id, this.current).then(response => {
                    CandyEvent.$emit('shipping-prices-updated');
                });
            }
        }
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Shipping method prices</h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-primary" @click="add()"><fa icon="plus"></fa> Add price</button>
                    </div>
                </div>
            </div> <!-- col-xs-12 -->
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rate</th>
                            <th>Currency</th>
                            <th>Min Basket Amount</th>
                            <th>Min Weight</th>
                            <th>Min Height</th>
                            <th>Min Width</th>
                            <th>Min Depth</th>
                            <th>Min Volume</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="price in prices">
                            <td>
                                {{ currency(price.rate) }}{{ price.rate }}
                            </td>
                            <td>
                                {{ price.currency.data.name }}
                            </td>
                            <td>
                                {{ price.min_basket }}
                            </td>
                            <td>
                                {{ price.min_weight }}{{ price.weight_unit }}
                            </td>
                            <td>
                                {{ price.min_height }}{{ price.height_unit }}
                            </td>
                            <td>
                                {{ price.min_width }}{{ price.width_unit }}
                            </td>
                            <td>
                                {{ price.min_depth }}{{ price.depth_unit }}
                            </td>
                            <td>
                                {{ price.min_volume }}{{ price.volume_unit }}
                            </td>
                            <th class="text-right" width="10%">
                                <button class="btn btn-sm btn-default btn-action" @click="edit(price)">
                                    <fa icon="edit"></fa>
                                </button>
                                <button class="btn btn-sm btn-default btn-action" @click="destroy(price)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
            <candy-modal :title="modalTitle" v-show="editing" @closed="current = {}">
                <div slot="body" class="text-left">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Rate</label>
                                <div class="input-group input-group-full">
                                    <span class="input-group-addon" v-html="symbol(current)"></span>
                                    <input type="number" class="form-control" v-model="current.rate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Currency</label>
                                <candy-select :options="currencySelect()" v-if="currencies.length" v-model="current.currency_id"></candy-select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Basket Amount</label>
                                <em class="help-txt">This is the inclusive minimum amount to get this price</em>
                                <div class="input-group input-group-full">
                                    <span class="input-group-addon" v-html="symbol(current)"></span>
                                    <input type="number" class="form-control" v-model="current.min_basket">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Width</label>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="current.min_width">
                                    <candy-select :options="['cm','mm', 'in']" v-model="current.width_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Weight</label>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="current.min_weight">
                                    <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="current.weight_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Height</label>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="current.min_height">
                                    <candy-select :options="['cm','mm', 'in']" v-model="current.height_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Depth</label>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="current.min_depth">
                                    <candy-select :options="['cm','mm', 'in']" v-model="current.depth_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Min Volume</label>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="current.min_volume">
                                    <candy-select :options="['l', 'ml']" v-model="current.volume_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <candy-customer-groups :groups="current.customer_groups.data" :cols="['visible']" v-if="current.customer_groups"></candy-customer-groups>
                </div>
                <template slot="footer">
                    <button class="btn btn-primary" @click="savePrice()">Save Options</button>
                </template>
            </candy-modal>
    </div>

</template>

<style lang="scss" scoped>
.disabled {
    opacity:.5;
    pointer-events: none;
}
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s;
    }
    .table td {
        width:12%;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
    {
        opacity: 0;
    }
</style>
