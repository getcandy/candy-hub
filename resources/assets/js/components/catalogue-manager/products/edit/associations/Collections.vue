<script>
    export default {
        data() {
            return {
                request: apiRequest,
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                deleteModalOpen: false,
                deleteModalData: {},
                foo: false,
                search: '',
                collections: null,
                tableParams: {
                    columns: [
                        {'name': '', 'width': '50px', 'type': 'image', 'align': 'left', 'source': 'name'},
                        {'name': 'Title', 'width': '*', 'type': 'attribute', 'align': 'left', 'source': 'name'},
                        {'name': 'URL', 'width': '50%', 'type': 'route', 'align': 'left', 'source': 'slug'}
                    ]
                }
            }
        },
        props: {
            productId: {
                type: String,
                required: true,
            },
            existing: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.collections = this.existing;
            });
        },
        methods: {
            getAttribute(data, attribute) {
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
            addNew(event) {
                this.collections = event;
                this.save();
            },
            removeCollection() {
                this.collections.splice(this.deleteModalData.index, 1);
                this.deleteModalOpen = false;
                CandyEvent.$emit('notification', {
                    level: 'success',
                    message: 'Collection removed'
                });
                this.request.send('delete', '/products/' + this.productId + '/collections/' + this.deleteModalData.id);
            },
            save() {

                const ids = _.map(this.collections, c => {
                    return c.id;
                });

                this.request.send('post', '/products/' + this.productId + '/collections', {'collections': ids})
                    .then(response => {
                        this.collectionsLoaded = true;
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.productCollections = response.data;
                        this.closeAddModal();
                    });
            },
            openDeleteModal(collection) {
                this.deleteModalData = {
                    'id': collection.id,
                    'index': this.collections.indexOf(collection),
                    'name': this.getAttribute(collection, 'name'),
                    'slug': this.getRoute(collection)
                };
                this.deleteModalOpen = true;
            },
            closeDeleteModal(collection) {
                this.deleteModalData = {};
                this.deleteModalOpen = false;
            },
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
                    <candy-collection-browser @saved="addNew" :current="collections" v-if="collections"></candy-collection-browser>
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
                    <tr v-for="collection in collections" :key="collection.id">
                        <td width="80">
                            <img src="/candy-hub/images/placeholder/no-image.svg" :alt="collection|attribute('name')">
                        </td>
                        <td>
                            {{ collection|attribute('name') }}
                        </td>
                        <td>
                            <!-- {{ getRoute(collection) }} -->
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(collection)">
                                <i class="fa fa-trash-alt" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="collections && !collections.length">
                    <tr>
                      <td colspan="4">
                        <span class="text-muted">No collections found</span>
                      </td>
                    </tr>
                </tfoot>
            </table>
        </div>

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