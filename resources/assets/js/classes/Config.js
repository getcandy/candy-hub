'use strict';

// export ApiRequest from './ApiRequest';

var ApiRequest = require('./ApiRequest');

class Config {

    constructor() {
        this.request = new ApiRequest();

        /**
         * Set up our channels
         * @type {Object}
         */
        this.channels = {
            requested: false,
            data: []
        }
    }

    /**
     * Get all our channels from the API
     * @return {[type]} [description]
     */
    get(config) {
        let response = [];
        return this.request.send('GET', config);
        return response;
    }
}

module.exports = Config;