'use strict';

class Flatify {

    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data = [], selectedData = []) {
        this.originalData = data;
        this.selectedData = selectedData;
    }

    all() {

        return [].concat.apply([], this.originalData);

    }

    selected(selection = []) {

        let flatify = [];

        this.originalData.forEach(function (data) {

            return 'dfs' + data;
        });

    }
}

module.exports = Flatify;