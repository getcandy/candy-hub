<script>
    export default {
        data() {
            return {
                loaded: false,
                groups: [],
                lang: locale.current(),
                params: {
                    per_page: 50,
                    includes: 'attributes',
                    page: 1
                },
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                },
                pagination: {}
            }
        },
        mounted() {
            this.load();
            CandyEvent.$on('group-added', product => {
                this.load();
            });
        },
        methods: {
            load() {
                apiRequest.send('get', '/attribute-groups', [], this.params)
                    .then(response => {
                        this.groups = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                    });
            },
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.groups.splice(oldIndex, 1)[0];
                this.groups.splice(newIndex, 0, movedItem);

                let pos = 1;
                this.groups.forEach(group => {
                    group.position = pos;
                    pos++;
                });
            },
            edit(id) {
                return route('hub.attribute-groups.edit', id);
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
                            <th width="5%"></th>
                            <th width="8%">ID</th>
                            <th>Name</th>
                            <th>Handle</th>
                            <th>No. Attributes</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded" v-sortable="sortableOptions">
                        <tr v-for="group in groups" :key="group.id">
                            <td class="handle">
                                <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Artboard" fill="#D8D8D8">
                                            <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                            <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                        </g>
                                    </g>
                                </svg>
                            </td>
                            <td>{{ group.id }}</td>
                            <td>
                                <a :href="edit(group.id)">
                                    {{ group.name|t }}
                                </a>
                            </td>
                            <td>{{ group.handle }}</td>
                            <td>{{ group.attributes.data.length }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="text-center" v-else>
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
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