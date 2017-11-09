<script>
    export default {
        data() {
            return {
                request: apiRequest,
                requestParams: {
                    per_page: 6,
                    current_page: 1,
                    keywords: '',
                    includes: 'routes'
                },
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                addModalOpen: false,
                selectedCollections: [],
                deleteModalOpen: false,
                deleteModalData: {},
                search: '',
                collections: [],
                collectionsLoaded: false,
                productCollections: [],
                tableParams: {
                    columns: [
                        {'name': '', 'width': '50px', 'type': 'image', 'align': 'left', 'source': 'name'},
                        {'name': 'Title', 'width': '*', 'type': 'attribute', 'align': 'left', 'source': 'name'},
                        {'name': 'URL', 'width': '50%', 'type': 'route', 'align': 'left', 'source': 'slug'}
                    ]
                }
            }
        },
        mounted() {
            if (!this.collections.length) {
                this.loadCollections();
            }
            this.productCollections = this.product.collections.data;

            this.productCollections.forEach(collection => {
                this.selectedCollections.push(collection.id);
            });
        },
        props: {
            product: {
                type: Object
            }
        },
        methods: {
            getAttribute(data, attribute) {
                console.log(this.channel);
                return data.attribute_data[attribute][this.channel][this.language];
            },
            getRoute(data) {
                let slug = '';
                data.routes.data.forEach(route => {
                    if (route.locale === this.language) {
                        slug = route.slug;
                    }
                });

                return slug;
            },
            loadCollections() {
                this.request.send('get', '/collections', [], this.requestParams)
                    .then(response => {
                        this.collections = response.data;
                        this.requestParams.total_pages = response.meta.pagination.total_pages;
                        this.collectionsLoaded = true;
                    });
            },
            removeCollection() {
                let productID = this.product.id;
                let collectionID = this.deleteModalData.id;

                this.productCollections.splice(this.deleteModalData.index, 1);

                this.deleteModalOpen = false;
                CandyEvent.$emit('notification', {
                    level: 'success',
                    message: 'Collection removed'
                });

                this.request.send('delete', '/products/' + productID + '/collections/' + collectionID);
            },
            changePage(page) {
                this.loaded = false;
                this.requestParams.current_page = page;
                this.loadCollections();
            },
            save() {
                let ids = [];
                _.forEach(this.selectedCollections, value => {
                    ids.push(value);
                });

                this.request.send('post', '/products/' + this.product.id + '/collections', {'collections': ids})
                    .then(response => {
                        this.collectionsLoaded = true;
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.productCollections = response.data;
                        this.closeAddModal();
                    });
            },
            closeAddModal() {
                this.addModalOpen = false;
            },
            openDeleteModal(collection) {
                this.deleteModalData = {
                    'id': collection.id,
                    'index': this.productCollections.indexOf(collection),
                    'name': this.getAttribute(collection, 'name'),
                    'slug': this.getRoute(collection)
                };
                this.deleteModalOpen = true;
            },
            closeDeleteModal(collection) {
                this.deleteModalData = {};
                this.deleteModalOpen = false;
            },
            addSelected(ids){
                this.selectedCollections = ids;
            }
        }
    }
</script>

<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h4>Collections</h4>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" @click="addModalOpen = true">
                        Add Collection
                    </button>
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Collection Name</td>
                    <td colspan="2">URL</td>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="collection in productCollections">
                        <td width="80">
                            <img src="/images/placeholder/no-image.svg" :alt="getAttribute(collection, 'name')">
                        </td>
                        <td>
                            {{ getAttribute(collection, 'name') }}
                        </td>
                        <td>
                            {{ getRoute(collection) }}
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(collection)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!productCollections.length">
                    <tr>
                      <td colspan="4">
                        <span class="text-muted">No collections found</span>
                      </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Add category to product Modal -->
        <candy-modal id="addModal" title="Add this product to collections" size="modal-lg" v-show="addModalOpen" @closed="closeAddModal()">

            <div slot="body">
                <div class="form-group">
                    <label class="sr-only">Search</label>
                    <input type="text" class="form-control search" v-model="search" placeholder="Search Collections">
                </div>
                <hr>

                <candy-table :items="collections" :loaded="collectionsLoaded" @selected="addSelected"
                                :associations="true"
                             :params="tableParams" :pagination="requestParams" @change="changePage"
                             :checked="selectedCollections">
                </candy-table>

            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="save()">Add to Collection</button>
            </div>

        </candy-modal>

        <!-- Delete category from product Modal -->
        <candy-modal id="deleteCollectionModal" title="Remove Collection?" size="modal-md" v-show="deleteModalOpen" @closed="closeDeleteModal()">

            <div slot="body">
                <p>
                    Are you sure you want to remove the <strong>"{{ deleteModalData.name }}"</strong> collection from this product?<br>
                </p>
            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="removeCollection()">Confirm Removal</button>
            </div>

        </candy-modal>

    </div>
</template>