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
                channel: 'ecommerce',
                language: locale.current(),
                addModalOpen: false,
                selectedCategories: [],
                deleteModalOpen: false,
                deleteModalData: {},
                search: '',
                categories: [],
                categoriesLoaded: false,
                productCategories: [],
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
            if (!this.categories.length) {
                this.loadCategories();
            }
            this.productCategories = this.product.categories.data;

            this.productCategories.forEach(category => {
                this.selectedCategories.push(category.id);
            });
        },
        props: {
            product: {
                type: Object
            }
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
            loadCategories() {
                this.request.send('get', '/categories', [], this.requestParams)
                    .then(response => {
                        this.categories = response.data;
                        this.requestParams.total_pages = response.meta.pagination.total_pages;
                        this.categoriesLoaded = true;
                    });
            },
            removeCategory() {
                let productID = this.product.id;
                let categoryID = this.deleteModalData.id;

                this.productCategories.splice(this.deleteModalData.index, 1);

                this.deleteModalOpen = false;
                CandyEvent.$emit('notification', {
                    level: 'success',
                    message: 'Category removed'
                });

                this.request.send('delete', '/products/' + productID + '/categories/' + categoryID);
            },
            changePage(page) {
                this.loaded = false;
                this.requestParams.current_page = page;
                this.loadCategories();
            },
            save() {
                let ids = [];
                _.forEach(this.selectedCategories, value => {
                    ids.push(value);
                });

                this.request.send('post', '/products/' + this.product.id + '/categories', {'categories': ids})
                    .then(response => {
                        this.categoriesLoaded = true;
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.productCategories = response.data;

                        this.closeAddModal();
                    });
            },
            closeAddModal() {
                this.addModalOpen = false;
            },
            openDeleteModal(category) {
                this.deleteModalData = {
                    'id': category.id,
                    'index': this.productCategories.indexOf(category),
                    'name': this.getAttribute(category, 'name'),
                    'slug': this.getRoute(category)
                };
                this.deleteModalOpen = true;
            },
            closeDeleteModal(category) {
                this.deleteModalData = {};
                this.deleteModalOpen = false;
            },
            addSelected(ids){
                this.selectedCategories = ids;
            }
        }
    }
</script>

<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h4>Categories</h4>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" @click="addModalOpen = true">
                        Add Category
                    </button>
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Category Name</td>
                    <td colspan="2">URL</td>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="category in productCategories">
                        <td width="80">
                            <img src="/images/placeholder/no-image.svg" :alt="getAttribute(category, 'name')">
                        </td>
                        <td>
                            {{ getAttribute(category, 'name') }}
                        </td>
                        <td>
                            {{ getRoute(category) }}
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(category)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!productCategories.length">
                    <tr>
                      <td colspan="4">
                        <span class="text-muted">No categories found</span>
                      </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Add category to product Modal -->
        <candy-modal id="addModal" title="Add this product to categories" size="modal-lg" v-show="addModalOpen" @closed="closeAddModal()">

            <div slot="body">
                <div class="form-group">
                    <label class="sr-only">Search</label>
                    <input type="text" class="form-control search" v-model="search" placeholder="Search Categories">
                </div>
                <hr>

                <candy-table :items="categories" :loaded="categoriesLoaded" @selected="addSelected"
                             :params="tableParams" :pagination="requestParams" @change="changePage"
                             :checked="selectedCategories">
                </candy-table>

            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="save()">Add to Categories</button>
            </div>

        </candy-modal>

        <!-- Delete category from product Modal -->
        <candy-modal id="deleteCategoryModal" title="Remove Category?" size="modal-md" v-show="deleteModalOpen" @closed="closeDeleteModal()">

            <div slot="body">
                <p>
                    Are you sure you want to remove the <strong>"{{ deleteModalData.name }}"</strong> category from this product?<br>
                </p>
            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="removeCategory()">Confirm Removal</button>
            </div>

        </candy-modal>

    </div>
</template>