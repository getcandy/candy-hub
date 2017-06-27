'use strict';


class ApiRequest {

    constructor() {
        this.errors = {};
    }

    getError(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    addErrors(errors) {
        this.errors = errors;
    }

    /**
     * Sends the request
     * @param  {String} method
     * @param  {String} path
     * @param  {Object} data
     */
    send(method, path, data) {
        axios({
          method: method,
          url: this.getUrl(path),
          data: data,
          headers: {'Accept': 'application/json'}
        })
        .then(response => {
            Event.$emit('notification', {
                level: 'success'
            });
        })
        .catch(error => {
            this.errors = error.response.data;
            Event.$emit('notification', {
                level: 'error',
                message: 'Whoops'
            });
        });
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