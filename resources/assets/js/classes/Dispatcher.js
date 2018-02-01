'use strict';

class Dispatcher {

    constructor() {
        this.methods = [];
    }

    add(ref, component) {
        console.log('Added ' + ref);
        this.methods[ref] = component;
    }

    resolve(ref) {
        return this.methods[ref];
    }

}
module.exports = Dispatcher;