<script>
    export default {
        data() {
            return {
                loaded: false,
                families: [],
                lang: locale.current(),
                params: {
                    per_page: 50,
                    includes: 'group',
                    page: 1
                },
                pagination: {}
            }
        },
        mounted() {
            this.load();
        },
        methods: {
            load() {
                apiRequest.send('get', '/product-families', [], this.params)
                    .then(response => {
                        this.families = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                    });
            },
            edit(id) {
                return route('hub.product-families.edit', id);
            },
            changePage(page) {
                this.loaded = false;
                this.params.page = page;
                this.loadCustomers();
            }
        }
    }
</script>

<template>
    <div>

        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-attributes">
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr v-for="family in families">
                            <td>{{ family.id }}</td>
                            <td>
                                <a :href="edit(family.id)">
                                    {{ family|attribute('name') }}
                                </a>
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