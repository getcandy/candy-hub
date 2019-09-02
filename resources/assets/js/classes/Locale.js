class Locale {
    constructor(locale) {
        this.locale = locale;
    }
    current() {
        return this.locale;
    }
}

module.exports = Locale;