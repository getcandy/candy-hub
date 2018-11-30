<template>
    <div>
        <div class="row" v-for="item in prices">
            <div class="col-md-4">
                <div class="form-group" >
                    <label>{{ item.group.data.name }}</label>
                    <price-input v-model="item.price"></price-input>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tax</label>
                    <candy-select :options="taxes" v-model="item.tax.data.id"></candy-select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PriceInput from '../../../../../elements/forms/inputs/PriceInput.vue';

    export default {
        components: {
            PriceInput
        },
        props: ['value', 'groups', 'price'],
        methods : {
            getVariantTax(variant) {
                if (variant.tax.data.length) {
                    return variant.tax.data.id;
                }
                return null;
            }
        },
        data() {
            return {
                prices: this.value
            }
        },
        mounted() {
            if (!this.prices.length) {
                _.each(this.groups, group => {
                    this.prices.push({
                        group: {
                            data: group
                        },
                        tax: {
                            data: _.find(this.$store.getters.getTaxes, tax => {
                                return tax.default;
                            })
                        },
                        price: this.price
                    })
                });
            }
        },
        computed: {
            taxes() {
                let options = [
                    {label: 'None', value: ' '}
                ];
                _.each(this.$store.getters.getTaxes, item => {
                    options.push({
                        label: item.name + ' (' + item.percentage + '%)',
                        value: item.id
                    });
                });
                return options;
            }
        }
    }
</script>

<style scoped>

</style>