<script>
    import HasGroups from '../../../mixins/HasGroups.js';
    import HasAttributes from '../../../mixins/HasAttributes.js';

    export default {
        mixins: [
            HasGroups,
            HasAttributes,
        ],
        data() {
            return {
                loaded: false,
                collections: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                params: {
                    per_page: 4,
                    current_page: 1,
                    includes: 'channels,assets,attributes.group,customerGroups'
                },
                pagination: {}
            }
        },
        watch: {
            selected: function(val) {
                this.checkedCount = val.length;
                this.selectAll = (val.length === this.collections.length);
            },
            selectAll: function(val) {
                let selected = [];

                if (val) {
                    this.collections.forEach(function (product) {
                        selected.push(product.id);
                    });
                }
                this.selected = selected;
            }
        },
        mounted() {
            this.loadCollections();
            CandyEvent.$on('collection-added', product => {
                this.loadCollections();
            });
        },
        methods: {
            loadCollections() {
                apiRequest.loadCollections(this.params)
                    .then(response => {
                        this.collections = response.data;
                        this.pagination = response.meta.pagination;
                        this.loaded = true;
                    });
            },
            thumbnail(product) {
                if (product.thumbnail) {
                    return product.thumbnail.data.thumbnail;
                }
                return '/candy-hub/images/placeholder/no-image.svg';
            },
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.loaded = false;
                this.params.current_page = page;
                this.loadCollections();
            },
            loadCollection: function (id) {
                location.href = route('hub.collections.edit', id);
            },
        }
    }
</script>

<template>
    <div>
        <div class="panel">
            <div class="panel-body">
                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th width="10%">Image</th>
                            <th width="25%">Collection</th>
                            <th width="19%">Display</th>
                            <th width="19%">Purchasable</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="collection in collections" :key="collection.id">
                            <td @click="loadCollection(collection.id)">
                                <img :src="thumbnail(collection)" :alt="collection.name">
                            </td>
                            <td @click="loadCollection(collection.id)">{{ collection.name }}</td>
                            <td @click="loadCollection(collection.id)">{{ visibility(collection, 'customer_groups') }}</td>
                            <td @click="loadCollection(collection.id)">{{ visibility(collection, 'channels') }}</td>
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
            <!-- </div> -->

        <!-- </div> -->
    </div>
</template>