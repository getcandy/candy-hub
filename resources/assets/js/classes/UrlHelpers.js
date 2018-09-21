'use strict';

export default {
    /**
     * Get all the URL params
     */
    params() {
        return new URLSearchParams(window.location.search);
    },
    /**
     * Set a url parameter
     */
    setParam(key, value) {
        var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
            urlQueryString = document.location.search,
            newParam = key + '=' + value,
            params = '?' + newParam;

        // If the "search" string exists, then build params from it
        if (urlQueryString) {

            let updateRegex = new RegExp('([\?&])' + key + '[^&]*');
            let removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');

            if (typeof value == 'undefined' || value == null || value == '') { // Remove param if value is empty

                params = urlQueryString.replace(removeRegex, "$1");
                params = params.replace(/[&;]$/, "");

            } else if (urlQueryString.match(updateRegex) !== null) { // If param exists already, update it

                params = urlQueryString.replace(updateRegex, "$1" + newParam);

            } else { // Otherwise, add it to end of query string

                params = urlQueryString + '&' + newParam;

            }

        }
        window.history.replaceState({}, "", baseUrl + params);
    }
}