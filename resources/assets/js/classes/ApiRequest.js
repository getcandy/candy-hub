'use strict';

class ApiRequest {

    constructor() {
        this.errors = {};
        this.products = [];
    }

    hasError(field) {
        return this.errors.hasOwnProperty(field);
    }

    getError(field) {
        if (this.errors['error'] && !field) {
            return this.errors.error.message;
        } else if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    /**
     * Clear one or all error fields.
     *
     * @param {string|null} field
     */
    clearError(field) {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }

    /**
     * Record the new errors.
     *
     * @param {object} errors
     */
    record(errors) {
        this.errors = errors;
    }

    /**
     * Sends the request
     * @param  {String} method
     * @param  {String} path
     * @param  {Object} data
     * @param  {Object} params
     */
    send(method, path, data, params) {

        return new Promise((resolve, reject) => {
            axios({
                method: method,
                url: this.getUrl(path),
                data: data,
                params: params,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                this.onSuccess(response.data);
                resolve(response.data);
            })
            .catch(error => {
                this.onFail(error.response.data);
                reject(error);
            });
        });

    }

    loadProducts(params, flatten = false) {

        let paramsArr = {'params': params};

        return new Promise((resolve, reject) => {
            axios.get('/api/v1/products', paramsArr)
                .then(response => {
                    resolve((flatten) ? this.productFlatify(response.data) : response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });

    }

    loadCollections(params, flatten = false) {

        let paramsArr = {'params': params};

        return new Promise((resolve, reject) => {
            axios.get('/api/v1/products', paramsArr)
                .then(response => {
                    resolve((flatten) ? this.productFlatify(response.data) : response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });

    }

    productFlatify(response) {

        let flatify = [];
        flatify['pagination'] = response['meta'].pagination;

        response['data'].forEach(function (product) {

            // Determine whether to show all, selected or none (Purchasable)
            let purchasableArr = jQuery.map(product.customer_groups.data, function( customer_group ) {
                return (customer_group.purchasable === true) ? customer_group.name : null;
            });

            let purchasableStr = '';
            if(purchasableArr.length === 0) { purchasableStr = 'None';}
            else if(purchasableArr.length === product.customer_groups.data.length) { purchasableStr = 'All'; }
            else { purchasableStr = purchasableArr.join(', '); }

            // Determine whether to show all, selected or none (Display)
            let displayArr = jQuery.map(product.channels.data, function( channel ) {
                return (channel.visible === true) ? channel.name : null;
            });
            let displayStr = '';
            if(displayArr.length === 0) {displayStr = 'None';}
            else if(displayArr.length === product.channels.data.length) {displayStr = 'All';}
            else {displayStr = displayArr.join(', ');}

            flatify.push({
                'id': product.id,
                'name': product.attribute_data.name.ecommerce,
                'customer_groups': product.customer_groups.data,
                'purchasable': purchasableStr,
                'channels': product.channels.data,
                'display': displayStr,
                'family_group': product.family.data.attribute_data,
                'group': product.family.data.attribute_data.name.ecommerce,
                'thumbnail' : product.thumbnail
            });
        });

        return flatify;

    }

    onSuccess(response){
        this.clearError();
    }

    onFail(response){
        this.record(response);
    }

    /**
     * Generates a useable URL for the request
     * @param  {String} path
     * @return {String}
     */
    getUrl(path) {
        return '/api/v1/' + path.replace(/^\/|\/$/g, '');
    }
}

module.exports = ApiRequest;