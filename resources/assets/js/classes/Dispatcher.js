'use strict';

class Dispatcher {
    constructor() {
        this.components = [];
    }
    add(handle, data) {
        this[handle] = data;
    }
    get(handle) {
        return this[handle];
    }
    dispatch(handle) {
        return new Promise((resolve, reject) => {
            let ref = this[handle];
            if (!ref) {
                reject();
            }
            ref.component[ref.method]();
            resolve();
        });
    }
}

module.exports = Dispatcher;