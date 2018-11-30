'use strict';

const axios = require('axios');

export default {
    fetched: false,
    fetching: false,
    groups: [],

    refresh() {
        this.fetching = true;

        return new Promise((resolve, reject) => {
            axios({
                method: 'GET',
                url: '/api/v1/customers/groups',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    this.groups = response.data;
                    this.fetched = true;
                    this.fetching = false;
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },

    getGroups() {

        if (!this.fetched) {
            this.fetching = true;
            this.refresh().then(response => {
                return this.groups;
            });
        }

        return this.groups;
    }
}