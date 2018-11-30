Vue.filter('t', function (value, lang) {
    if (typeof value === 'string') {
        return value;
    }
    if (!lang) {
        lang = locale.current();
    }
    if (!value[lang]) {
        return value[Object.keys(value)[0]];
    }
    return value[lang];
});