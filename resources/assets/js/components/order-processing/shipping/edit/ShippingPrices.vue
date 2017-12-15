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
                newPrice: {
                    currency_id: '',
                    rate: '',
                    fixed: '',
                    min_weight: 0,
                    weight_unit: 'kg',
                    min_height: 0,
                    height_unit: 'cm',
                    min_width: 0,
                    width_unit: 'cm',
                    min_depth: 0,
                    depth_unit: 'cm',
                    min_volume: 0,
                    volume_unit: 'l'
                },
                currencies: []
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
                this.newPrice.currency_id = _.find(this.currencies, item => {
                    return item.default;
                }).id;
            });
            this.prices = this.method.prices.data;

            _.each(this.prices, price => {
                this.$set(price, 'currency_id', price.currency.data.id);
            });
        },
        computed: {
            
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
            isEditing(price) {
                return this.editable.length || price.id === this.editable.id;
            },
            setEditing(price) {
                if (this.editable.length) {
                    this.saveEditable(price);
                }
                this.editable = price;
            },
            savePrice(price)
            {
                apiRequest.send('put', 'shipping/prices/' + this.editable.id, this.editable).then(response => {
                });
                this.setEditing({});
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
                apiRequest.send('delete', '/shipping/prices/' + price.id);
            },
            create() {
                apiRequest.send('post', '/shipping/' + this.method.id + '/prices', this.newPrice)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('shipping-price-added', {
                            price: this.price
                        });
                        this.modalOpen = false;
                        this.prices.push(response.data);
                        this.newPrice = {
                            currency_id: '',
                            rate: '',
                            fixed: '',
                            min_weight: 0,
                            weight_unit: 'kg',
                            min_height: 0,
                            height_unit: 'cm',
                            min_width: 0,
                            width_unit: 'cm',
                            min_depth: 0,
                            depth_unit: 'cm',
                            min_volume: 0,
                            volume_unit: 'l'
                        };
                    }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
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
                </div>
            </div> <!-- col-xs-12 -->
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rate</th>
                            <th>Currency</th>
                            <th>Min Weight</th>
                            <th>Min Height</th>
                            <th>Min Width</th>
                            <th>Min Depth</th>
                            <th>Min Volume</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>
                                <div class="input-group input-group-full">
                                    <span class="input-group-addon" v-html="symbol(newPrice)"></span>
                                    <input type="number" class="form-control" v-model="newPrice.rate">
                                </div>   
                            </th>
                            <th>
                                <candy-select :options="currencySelect()" v-if="currencies.length" v-model="newPrice.currency_id"></candy-select>
                            </th>
                            <th>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="newPrice.min_weight">
                                    <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="newPrice.weight_unit"
                                                    :addon="true"></candy-select>
                                </div>
                            </th>
                            <th>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="newPrice.min_height">
                                    <candy-select :options="['cm','mm', 'in']" v-model="newPrice.height_unit"
                                                    :addon="true"></candy-select>
                                </div>   
                            </th>
                            <th>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="newPrice.min_width">
                                    <candy-select :options="['cm','mm', 'in']" v-model="newPrice.width_unit"
                                                    :addon="true"></candy-select>
                                </div> 
                            </th>
                            <th>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="newPrice.min_depth">
                                    <candy-select :options="['cm','mm', 'in']" v-model="newPrice.depth_unit"
                                                    :addon="true"></candy-select>
                                </div> 
                            </th>
                            <th>
                                <div class="input-group input-group-full">
                                    <input type="number" class="form-control" v-model="newPrice.min_volume">
                                    <candy-select :options="['cm','mm', 'in']" v-model="newPrice.volume_unit"
                                                    :addon="true"></candy-select>
                                </div> 
                            </th>
                            <th class="text-right">
                                <button class="btn btn-sm btn-success" @click="create()" type="button">Add Price</button>
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr v-for="price in prices">
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <span class="input-group-addon" v-html="symbol(price)"></span>
                                        <input type="number" class="form-control" v-model="editable.rate">
                                    </div>                                                          
                                </template>
                                <template v-else>
                                    {{ price.rate }}
                                </template>
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <candy-select :options="currencySelect()" v-if="currencies.length" v-model="editable.currency_id"></candy-select>                                                           
                                </template>
                                <template v-else>
                                    {{ price.currency.data.name }}
                                </template>
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <input type="number" class="form-control" v-model="editable.min_weight">
                                        <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="editable.weight_unit"
                                                        :addon="true"></candy-select>
                                    </div>                                                    
                                </template>
                                <template v-else>
                                    {{ price.min_weight }}{{ price.weight_unit }}
                                </template>
                                
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <input type="number" class="form-control" v-model="editable.min_height">
                                        <candy-select :options="['cm','mm', 'in']" v-model="editable.height_unit"
                                                        :addon="true"></candy-select>
                                    </div>                                                    
                                </template>
                                <template v-else>
                                    {{ price.min_height }}{{ price.height_unit }}
                                </template>
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <input type="number" class="form-control" v-model="editable.min_width">
                                        <candy-select :options="['cm','mm', 'in']" v-model="editable.width_unit"
                                                        :addon="true"></candy-select>
                                    </div>                                                    
                                </template>
                                <template v-else>
                                    {{ price.min_width }}{{ price.width_unit }}
                                </template>
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <input type="number" class="form-control" v-model="editable.min_depth">
                                        <candy-select :options="['cm','mm', 'in']" v-model="editable.depth_unit"
                                                        :addon="true"></candy-select>
                                    </div>                                                    
                                </template>
                                <template v-else>
                                    {{ price.min_depth }}{{ price.depth_unit }}
                                </template>
                            </td>
                            <td>
                                <template v-if="isEditing(price)">
                                    <div class="input-group input-group-full">
                                        <input type="number" class="form-control" v-model="editable.min_volume">
                                        <candy-select :options="['cm','mm', 'in']" v-model="editable.volume_unit"
                                                        :addon="true"></candy-select>
                                    </div>                                                    
                                </template>
                                <template v-else>
                                    {{ price.min_volume }}{{ price.volume_unit }}
                                </template>
                            </td>
                            <th class="text-right" width="10%">
                                <button class="btn btn-sm btn-default btn-action" @click="setEditing(price)" v-if="!isEditing(price)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-success btn-action" @click="savePrice(price)" v-else>
                                    <i class="fa fa-check"></i>
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
    </div>

</template>

<style lang="scss" scoped>
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
