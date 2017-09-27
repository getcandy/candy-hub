'use strict';

class Dispatcher {

    constructor() {

        this.methods = [];

    }

    add(ref, component) {

        this.methods[ref] = component;

    }

    resolve(ref) {
console.log(this);
        return this.methods[ref];

    }

}

module.exports = Dispatcher;