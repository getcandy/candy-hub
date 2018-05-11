<script>
    export default {
        data() {
            return {
                loaded: false,
                attributes: [],
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
            CandyEvent.$on('attribute-added', product => {
                this.load();
            });
        },
        methods: {
            load() {
                apiRequest.send('get', '/attributes', [], this.params)
                    .then(response => {
                        this.attributes = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                    });
            },
            edit(id) {
                return route('hub.attributes.edit', id);
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Handle</th>
                            <th>Group</th>
                            <th>Type</th>
                            <th>Searchable</th>
                            <th>Required</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr v-for="attribute in attributes">
                            <td>{{ attribute.id }}</td>
                            <td>
                                <a :href="edit(attribute.id)">
                                    {{ attribute.name[lang] }}
                                </a>
                            </td>
                            <td><code>{{ attribute.handle }}</code></td>
                            <td>{{ attribute.group.data.name[lang] }}</td>
                            <td>{{ attribute.type }}</td>
                            <td>
                                <template v-if="attribute.searchable">
                                    <fa icon="check" class="text-success"></fa>
                                </template>
                                <template v-else>
                                    <fa icon="times" class="text-danger"></fa>
                                </template>
                            </td>
                            <td>
                                <template v-if="attribute.required">
                                    <fa icon="check" class="text-success"></fa>
                                </template>
                                <template v-else>
                                    <fa icon="times" class="text-danger"></fa>
                                </template>
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