<script>
    export default {
        data() {
            return {
                request: apiRequest,
                modalOpen: false,
                currencies: [],
                zones: [],
                regions: [],
                zone: {
                    name: '',
                    countries: []
                },
                price: {
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
                }
            }
        },
        props: {
            method: {
                type: Object,
                default() {
                    return {};
                }
            },
            showModal: {
                type: Boolean,
                default: false
            }
        },
        created() {
            this.modalOpen = this.showModal;
        },
        mounted() {
            apiRequest.send('get', 'currencies').then(response => {
                this.currencies = response.data;
                this.price.currency_id = _.find(this.currencies, item => {
                    return item.default;
                }).id;
            });
        },
        computed: {
            symbol() {
                if (!this.currency) {
                    return '';
                }
                return this.currency.format.replace('{price}', '');
            },
            currency() {
                return _.find(this.currencies, item => {
                    return this.price.currency_id == item.id;
                })
            },
            basePrice() {
                return {
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
            }
        },
        methods: {
            toggle(region) {
                return '#' + region;
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
            save() {
                this.request.send('post', '/shipping/' + this.method.id + '/prices', this.price)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('shipping-price-added', {
                            price: this.price
                        });
                        this.modalOpen = false;
                        this.price = this.basePrice();
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
        <button class="btn btn-primary" @click="modalOpen = true">Add price</button>
        <candy-modal title="Add Shipping Price" v-show="modalOpen" @closed="modalOpen = false">
            <div slot="body" class="text-left">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Shipping Rate</label>
                            <div class="input-group input-group-full">
                                <span class="input-group-addon" v-html="symbol"></span>
                                <input type="number" class="form-control" v-model="price.rate">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Currency</label>
                            <candy-select :options="currencySelect()" v-if="currencies.length" v-model="price.currency_id"></candy-select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label>
                                Minimum Weight
                            </label>
                            <div class="input-group input-group-full">
                                <input type="number" class="form-control" v-model="price.min_weight">
                                <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="price.weight_unit"
                                                :addon="true"></candy-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                Minimum Height
                            </label>
                            <div class="input-group input-group-full">
                                <input type="number" class="form-control" v-model="price.min_height">
                                <candy-select :options="['cm','mm', 'in']" v-model="price.height_unit"
                                                :addon="true"></candy-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                Minimum Width
                            </label>
                            <div class="input-group input-group-full">
                                <input type="number" class="form-control" v-model="price.min_width">
                                <candy-select :options="['cm','mm', 'in']" v-model="price.width_unit"
                                                :addon="true"></candy-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>
                                Depth Width
                            </label>
                            <div class="input-group input-group-full">
                                <input type="number" class="form-control" v-model="price.min_depth">
                                <candy-select :options="['cm','mm', 'in']" v-model="price.depth_unit"
                                                :addon="true"></candy-select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <template slot="footer">
                <button class="btn btn-primary" @click="save()">Save Price</button>
            </template>
        </candy-modal>
    </div>
</template>
