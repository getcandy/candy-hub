<script>
    export default {
        data() {
            return {
                loaded: false,
                collections: [],
                selected: [],
                selectAll: false,
                checkedCount: 0,
                params: {
                    per_page: 25,
                    current_page: 1,
                    includes: 'channels,customer_groups,family,attribute_groups'
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
                return '/hub/images/placeholder/no-image.svg';
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
                location.href = '/hub/catalogue-manager/collections/' + id;
            },
            getVisibilty(collection, ref) {
                let groups = collection[ref].data;
                let visible = [];
                groups.forEach(group => {
                    let label = group.name;
                    // If this is time based visibility, we need to account for it.
                    if (group.hasOwnProperty('published_at')) {
                        // Is this visible?
                        if (group.published_at) {
                            // Is it in the future or is it now.
                            let date = moment(group.published_at),
                                now = moment();
                            if (date.isAfter(now)) {
                                label += ' ' + date.fromNow();
                            }
                            visible.push(label);
                        }
                    } else if (group.visible) {
                        visible.push(group.name);
                    }
                });
                if (visible.length == groups.length) {
                    return 'All';
                }
                if (!visible.length) {
                    return 'None';
                }
                return visible.join(', ');
            },
            getAttributeGroups(collection) {
                let groups = collection.attribute_groups.data,
                    visible = [];

                groups.forEach(group => {
                    visible.push(group.name);
                });

                // if (visible.length == groups.length) {
                //     return 'All';
                // }
                if (!visible.length) {
                    return 'None';
                }

                return visible.join(', ');
            },
        }
    }
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#all-collections" aria-controls="all-products" role="tab" data-toggle="tab">
                    All Collections
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-collections">

                <!-- Search Form -->
                <form>
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-8">

                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search">
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-2">

                            <button type="submit" class="btn btn-default btn-full" @click.prevent="loadCollections();">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>

                        </div>
                    </div>
                </form>
                <hr>

                <table class="table table-striped collection-table">
                    <thead>
                        <tr>
                            <th width="10%">Image</th>
                            <th width="25%">Collection</th>
                            <th width="19%">Display</th>
                            <th width="19%">Purchasable</th>
                            <th width="19%">Group</th>
                        </tr>
                    </thead>
                    <tbody v-if="loaded">
                        <tr class="clickable" v-for="collection in collections">
                            <td @click="loadCollection(collection.id)">
                                <img :src="thumbnail(collection)" :alt="collection.name">
                            </td>
                            <td @click="loadCollection(collection.id)">{{ collection|attribute('name') }}</td>
                            <td @click="loadCollection(collection.id)">{{ getVisibilty(collection, 'customer_groups') }}</td>
                            <td @click="loadCollection(collection.id)">{{ getVisibilty(collection, 'channels') }}</td>
                            <td @click="loadCollection(collection.id)">{{ getAttributeGroups(collection) }}</td>

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