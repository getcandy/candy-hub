
export default class Base {
    constructor() {
        this.params = {};
        this.version = 'v1';
        console.log(axios);
    }

    page(page) {
        this.params.page = page;
        return this;
    }

    limit(limit) {
        this.params.per_page = limit;
        return this;
    }

    include(includes) {
        if (Array.isArray(includes)) {
            includes = includes.join(',');
        }
        this.params.includes = includes;
        return this;
    }

    get() {
        return this.resolve('GET', this.endpoint());
    }

    resolve(method, endpoint, data) {
        let config = {
            method: method,
            url: '/api/v1/' + endpoint.replace(/^\/|\/$/g, ''),
            data: data,
            params: this.params,
            headers: {
                'Accept': 'application/json'
            }
        };
        return new Promise((resolve, reject) => {
            axios(config)
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

    /**
     * Determines if a field has an error
     * @param {string} field
     */
    hasError(field) {
        return this.errors.hasOwnProperty(field);
    }

    /**
     * Get the error for a field
     * @param {string} field
     */
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

    onSuccess(response) {
        this.clearError();
    }

    onFail(response) {
        this.record(response);
    }
}