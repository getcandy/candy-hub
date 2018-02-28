class Locale {
    constructor() {
        this.locale = document.documentElement.lang;
    }
    current() {
        return this.locale;
    }
}

module.exports = Locale;