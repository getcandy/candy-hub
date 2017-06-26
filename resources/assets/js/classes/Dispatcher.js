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
            console.log(ref);
            if (!ref) {
                reject();
            }
            console.log(ref);
            ref.save();
            resolve();
        });
    }
}

module.exports = Dispatcher;