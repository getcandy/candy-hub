<script>
    export default {
        data() {
            return {
                request: apiRequest,
                requestParams: {
                    per_page: 6,
                    current_page: 1,
                    keywords: ''
                },
                addModalOpen: false,
                selectedUsers: [],
                deleteModalOpen: false,
                deletable: {},
                search: '',
                users: [],
                usersLoaded: false,
                methodUsers: [],
                /**
                    Adding associations
                 */
                results: [],
                loading: false,
                meta: []
            }
        },
        mounted() {
            if (!this.users.length) {
                this.loadUsers();
            }
            this.methodUsers = this.method.users.data;

            this.methodUsers.forEach(user => {
                this.selectedUsers.push(user.id);
            });
        },
        props: {
            method: {
                type: Object
            }
        },
        methods: {
            loadUsers() {
                this.request.send('get', '/customers', [], this.requestParams)
                    .then(response => {
                        this.users = response.data;
                        this.requestParams.total_pages = response.meta.pagination.total_pages;
                        this.usersLoaded = true;
                    });
            },
            remove() {
                this.methodUsers.splice(this.methodUsers.indexOf(this.deletable), 1);
                this.deleteModalOpen = false;
                CandyEvent.$emit('notification', {
                    level: 'success',
                    message: 'User removed'
                });
                this.request.send('delete', '/shipping/' + this.method.id + '/users/' + this.deletable.id);
            },
            changePage(page) {
                this.results = [];
                this.loading = true;
                this.requestParams.current_page = page;
                this.getResults(this.keywords);
            },
            save() {
                this.request.send('put', '/shipping/' + this.method.id + '/users', {'users': this.selectedUsers})
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.results = [];
                        this.closeAddModal();
                    });
            },
            closeAddModal() {
                this.addModalOpen = false;
            },
            openDeleteModal(user) {
                this.deletable = user;
                this.deleteModalOpen = true;
            },
            closeDeleteModal(category) {
                this.deletable = {};
                this.deleteModalOpen = false;
            },
            assign(user) {
                this.selectedUsers.push(user.id);
                this.methodUsers.push(user);
            },
            detatch(user) {
                this.selectedUsers.splice(this.selectedCategories.indexOf(user.id), 1);
                this.methodUsers.splice(this.selectedCategories.indexOf(user), 1);
            },
            getResults() {
                this.requestParams.keywords = this.keywords;
                let results = this.request.send('GET', 'customers', {}, this.requestParams).then(response => {
                    this.results = response.data;
                    this.requestParams.total_pages = response.meta.pagination.total_pages;
                    this.meta = response.meta;
                    this.loading = false;
                });
            },
            alreadyLinked(user) {
                return this.selectedUsers.contains(user.id);
            },
            updateKeywords: _.debounce(function (e) {
                this.results = [];
                this.loading = true;
                this.keywords = e.target.value;
                this.getResults();
            }, 500)
        } // end
    }
</script>

<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h4>Shipping method users</h4>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" @click="addModalOpen = true">
                        Add User
                    </button>
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="user in methodUsers">
                        <td>
                            {{ user.first_name }} {{ user.last_name }}
                        </td>
                        <td>
                            {{ user.email }}
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(user)">
                                <fa icon="trash"></fa>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!methodUsers.length">
                    <tr>
                      <td colspan="4">
                        <span class="text-muted">No users found</span>
                      </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Add category to product Modal -->
        <candy-modal id="addModal" title="Add users to shipping method" size="modal-lg" v-show="addModalOpen" @closed="closeAddModal()">

            <div slot="body">
                <div class="form-group">
                    <label class="sr-only">Search</label>
                    <input type="text" class="form-control search" v-model="search" placeholder="Search by name or email" v-on:input="updateKeywords">
                </div>
                <hr>
                <table class="table association-table">
                    <thead>
                        <tr>
                            <th width="30%">Name</th>
                            <th width="30%">Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot v-if="loading" class="text-center">
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody class="list">
                        <tr v-for="user in results">
                            <td width="30%">
                                {{ user.first_name }} {{ user.last_name }}
                            </td>
                            <td width="30%">
                                {{ user.email }}
                            </td>
                            <td align="right">
                                <button @click="assign(user)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(user)">
                                    <fa icon="plus"></fa>
                                </button>
                                <button @click="detatch(user)" class="btn btn-sm btn-default btn-action" v-else>
                                    <fa icon="trash"></fa>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center">
                    <candy-table-paginate :pagination="requestParams" @change="changePage" v-if="!loading"></candy-table-paginate>
                </div>
                <!-- <candy-table :items="categories" :loaded="categoriesLoaded" @selected="addSelected"
                                :associations="true"
                             :params="tableParams" :pagination="requestParams" @change="changePage"
                             :checked="selectedCategories">
                </candy-table> -->

            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="save()">Save Assignments</button>
            </div>

        </candy-modal>

        <!-- Delete category from product Modal -->
        <candy-modal id="deleteModal" title="Remove User?" size="modal-md" v-show="deleteModalOpen" @closed="closeDeleteModal()">

            <div slot="body">
                <p>
                    Are you sure you want to remove the <strong>"{{ deletable.first_name }} {{ deletable.last_name }}"</strong> from this shipping method?<br>
                </p>
            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="remove()">Confirm Removal</button>
            </div>

        </candy-modal>

    </div>
</template>