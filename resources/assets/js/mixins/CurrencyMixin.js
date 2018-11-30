module.exports = {
    data() {
        return {
            currencies: []
        }
    },
    methods: {
        getCurrencies() {
            apiRequest.send('get', 'currencies').then(response => {
                this.currencies = response.data;
                this.defaultCurrency = _.find(this.currencies, item => {
                    return item.default;
                }).id;
            });
        }
    }
}
