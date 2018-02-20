<script>
    export default {
        data() {
            return {
                users: [],
                keywords: null,
                loading: false,
                payload: {},
                selected: [],
                params: {
                    per_page: 25,
                    current_page: 1
                }
            }
        },
        props: {
            criteria: {
                type: String
            },
            thing: {
                type: {}
            }
        },
        mounted() {
            // apiRequest.send('GET', 'users', {}, {keywords: this.params}).then(response => {
            //     this.users = response.data;
            // });
            this.payload = this.criteria;
            if (!this.payload.value) {
                this.$set(this.payload, 'value', []);
            } else {
                this.getSelectedModels();
            }
        },
        methods: {
            sync() {
                this.payload.value = _.map(this.selected, item => {
                    return item.id;
                });
            },
            searchUsers() {
                this.loading = true;
                apiRequest.send('GET', 'users', [], this.params)
                    .then(response => {
                        this.users = response.data;
                        this.params.total_pages = response.meta.pagination.total_pages;
                        this.meta = response.meta;
                        this.loading = false;
                    });
            },
            remove(id) {
                this.criteria.users.splice(this.criteria.users.indexOf(id), 1);
            },
            unselect(index) {
                this.selected.splice(index, 1);
                this.sync();
            },
            getSelectedModels() {
                apiRequest.send('GET', 'users', [], {
                    'ids' : this.payload.value
                })
                .then(response => {
                    this.selected = response.data;
                });
            },
            search: _.debounce(function () {
                    this.loading = true;
                    this.users = [];
                    this.params['keywords'] = this.keywords;
                    if (this.keywords) {
                        this.searchUsers();
                    }
                }, 500
            )
        }
    }
</script>


<template>
    <div>
        <h5>User in list</h5>
        <div class="filters">
            <div class="filter active" v-for="(user, index) in selected">
                {{ user.name }}
                <button class="delete" @click="unselect(index)"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
        <hr>
        <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tfoot>
                <tr v-if="loading">
                    <td colspan="2">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>
                        <label>
                            <input type="checkbox" v-model="selected" @change="sync" :value="user">
                            {{ user.name }}
                        </label>
                    </td>
                    <td>
                        {{ user.email }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
