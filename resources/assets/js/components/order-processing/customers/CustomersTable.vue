<script>
    export default {
        data() {
            return {
                loaded: false,
                customers: [],
                keywords: null,
                params: {
                    per_page: 50,
                    page: 1,
                    includes: 'groups,details'
                },
                pagination: {}
            }
        },

        mounted() {
            this.loadCustomers();
        },
        methods: {
            loadCustomers() {
                apiRequest.send('get', '/customers', [], this.params)
                    .then(response => {
                        this.customers = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                    });
            },
            details(customer) {
                return customer.details.data;
            },
            changePage(page) {
                this.loaded = false;
                this.params.page = page;
                this.loadCustomers();
            },
            loadCustomer: function (id) {
                location.href = route('hub.customers.edit', id);
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.params['keywords'] = this.keywords;
                    this.params['page'] = 1;
                    this.loadCustomers();
                }, 500
            ),
        }
    }
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#all-orders" aria-controls="all-orders" role="tab" data-toggle="tab">
                    All Customers
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-collections">

                <!-- Search Form -->
                <form>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>
                        </div>
                    </div>
                </form>
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="10%">Customer name</th>
                            <th width="19%">Company name</th>
                            <th width="19%">Email</th>
                            <th>Customer Group(s)</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="customer in customers">
                            <td @click="loadCustomer(customer.id)">{{ customer.id }}</td>
                            <td @click="loadCustomer(customer.id)">
                                <template v-if="details(customer).firstname">
                                    {{ details(customer).firstname }} {{ details(customer).lastname }}
                                </template>
                                <span v-else class="text-muted"><small>-</small></span>
                            </td>
                            <td @click="loadCustomer(customer.id)">
                                <span v-if="details(customer).company_name">{{ details(customer).company_name }}</span>
                                <span v-else class="text-muted"><small>-</small></span>
                            </td>
                            <td @click="loadCustomer(customer.id)">{{ customer.email }}</td>
                            <td @click="loadCustomer(customer.id)">
                                <span v-for="group, index in customer.groups.data">{{ group.name }}<span v-if="index + 1 != customer.groups.data.length" class="text-muted">&nbsp;&middot;&nbsp;</span></span>
                            </td>
                        </tr>


                    </tbody>
                    <tfoot class="text-center" v-else>
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="text-center">
                    <candy-table-paginate :pagination="pagination" @change="changePage"></candy-table-paginate>
                </div>
            </div>

        </div>
    </div>
</template>