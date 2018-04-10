module.exports = {
    data() {
        return {
            statuses: []
        }
    },
    methods: {
        getStatuses() {
            apiRequest.send('GET', '/settings/orders').then(response => {
                if (response.data.data) {
                    this.statuses = response.data.data.statuses;
                }
            });
        },
        status(status) {
            if (!this.statuses[status]) {
                return 'Unknown';
            }
            return this.statuses[status];
        }
    }
}
