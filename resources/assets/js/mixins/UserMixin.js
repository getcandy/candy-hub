module.exports = {
    data() {
        return {
            user : null
        }
    },
    computed: {
        isAdmin() {
            return this.hasRole('admin');
        }
    },
    methods: {
        checkForRole(role) {
            if (!this.user.roles.data.length) {
                return false;
            }
            return _.find(this.user.roles.data, item => {
                return item.name == role;
            });
        },
        hasRole(role) {
            if (!this.user) {
                apiRequest.send('GET', 'users/current', {}, {
                    'includes': 'roles'
                }).then(response => {
                    this.user = response.data;
                    this.checkForRole(role);
                });
            } else {
                return this.checkForRole(role);
            }

        }
    }
}
