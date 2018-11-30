'use strict'

export default {
    code: 'en',

    setCode(code) {
        this.code = code;
    },

    trans(obj) {
        if (obj[this.code]) {
            return obj[this.code];
        }
    }
}