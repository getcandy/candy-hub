<script>
    export default {
        data() {
            return {
                users: []
            }
        },
        props: {
            criteria: {
                type: Object
            }
        },
        computed: {
            selected() {
                return _.filter(this.users, function (user) {
                    return _.includes(this.criteria.users, user.id);
                });
            }
        },
        mounted() {
            apiRequest.send('GET', 'users').then(response => {
                this.users = response.data;
            });
        },
        methods: {
        }
    }
</script>


<template>
    <div>
        {{ selected }}
    </div>
</template>
