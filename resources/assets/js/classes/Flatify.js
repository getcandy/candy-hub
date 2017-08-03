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
        return $.map(this.originalData, function(n){
            return n;
        });
    }

    selected(selection = []) {

        if(selection.length !== 0){
            this.selectedData = selection;
        }

        if(this.selectedData.length === 0){
            return false;
        }

        return $.inArray(this.selectedData, this.originalData.all());

    }
}

module.exports = Flatify;