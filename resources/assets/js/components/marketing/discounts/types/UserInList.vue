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
                return _.filter(this.users, user => {
                    return _.includes(this.criteria.users, user.id);
                });
            }
        },
        mounted() {
            console.log(this.criteria);
            apiRequest.send('GET', 'users').then(response => {
                this.users = response.data;
            });
        },
        methods: {
            remove(id) {
                this.criteria.users.splice(this.criteria.users.indexOf(id), 1);
            }
        }
    }
</script>


<template>
    <div>
        <h5>User in list</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in selected" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td><button class="btn btn-sm btn-default btn-action" @click="remove(user.id)"><i class="fa fa-trash-o"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
