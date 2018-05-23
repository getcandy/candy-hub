<template>
    <div>
        <slot :symbol="symbol" :rate="rate"></slot>
    </div>
</template>

<script>
    import CurrencyService from '../../classes/Currency.js';

    export default {

        props: {
            current: {
                type: String,
                default: null,
            }
        },
        data() {
            return {
                service: CurrencyService,
                rate: 1,
                currencies: [],
                symbol: '&pound;'
            }
        },
        mounted() {
            this.currencies = this.service.getCurrencies();
        },
        watch: {
            currencies() {
                let currency;

                if (this.currencies.length > 1) {
                    currency = _.find(this.currencies, item => {
                        return this.current ? (item.code == this.current) : item.default;
                    });
                    this.symbol = currency.format.replace('{price}', '');
                    this.rate = currency.exchange_rate;
                }
                return '&dollar;';
            }
        }
    }
</script>