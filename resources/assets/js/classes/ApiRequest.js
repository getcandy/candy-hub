'use strict';

class ApiRequest {

    constructor() {
        this.errors = {};
    }

    hasError(field) {
        return this.errors.hasOwnProperty(field);
    }

    getError(field) {
        if (this.errors[field]) {
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
                headers: {'Accept': 'application/json'}
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