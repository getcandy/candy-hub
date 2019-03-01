module.exports = {
    data() {
        return {
            statuses: [],
            favourites: [],
            statusSelect: [],
            types: [],
            typeSelect: [],
            config: {},
        }
    },
    methods: {
        getStatuses() {
            this.initConfig();
        },
        initConfig() {
            apiRequest.send('GET', '/settings/orders').then(response => {
                if (response.data) {
                    this.statuses = response.data.statuses.options;
                    this.favourites = _.pickBy(this.statuses, status => {
                        return status.favourite;
                    });

                    this.statusSelect = _.map(this.statuses, (status, handle) => {
                        return {
                            label: status.label,
                            value: handle,
                        }
                    })

                    this.config = response.data;
                }
            });
        },
        getTypes() {
            apiRequest.send('GET', '/orders/types').then(response => {
                if (response.data) {
                    this.types = response.data;

                    this.typeSelect = _.map(this.types, (type) => {
                        return {
                            label: type.label ? type.label : 'Unknown',
                            value: type.label ? type.label : 'Unknown',
                        }
                    })
                }
            });
        },
        getStyles(status) {
            if (!this.statuses[status]) {
                return {
                    'color' : '#333',
                };
            }

            const statusObject = this.statuses[status];

            let color = this.hexToRgb(statusObject.color);

            return {
                'color' : statusObject.color,
                'border': 'transparent',
                'padding' : 0,
            };
        },
        hexToRgb(hex) {
            // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
            var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            hex = hex.replace(shorthandRegex, function(m, r, g, b) {
                return r + r + g + g + b + b;
            });

            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
        },
        lightenDarken(col, amt) {
            var usePound = false;

            if (col[0] == "#") {
                col = col.slice(1);
                usePound = true;
            }

            var num = parseInt(col,16);

            var r = (num >> 16) + amt;

            if (r > 255) r = 255;
            else if  (r < 0) r = 0;

            var b = ((num >> 8) & 0x00FF) + amt;

            if (b > 255) b = 255;
            else if  (b < 0) b = 0;

            var g = (num & 0x0000FF) + amt;

            if (g > 255) g = 255;
            else if (g < 0) g = 0;

            return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
        },
        status(status) {
            if (!this.statuses[status]) {
                return 'Unknown';
            }
            return this.statuses[status].label;
        }
    }
}
