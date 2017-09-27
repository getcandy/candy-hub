<script>
    export default {
        data() {
            return {
                request: apiRequest,
                channel: 'ecommerce',
                language: locale.current(),
                addModalOpen: false,
                selectedCategories: [],
                deleteModalOpen: false,
                deleteModalData: {},
                search: '',
                categoriesList: [],
                categoriesLoaded: false,
                tableParams: {
                    columns: [
                        {'name': '', 'width': '50px', 'type': 'image', 'align': 'left', 'source': 'name'},
                        {'name': 'Title', 'width': '*', 'type': 'attribute', 'align': 'left', 'source': 'name'},
                        {'name': 'URL', 'width': '50%', 'type': 'route', 'align': 'left', 'source': 'slug'},
                        {'name': '', 'width': '50px', 'type': 'checkbox', 'align': 'center', 'source': ''}
                    ]
                }
            }
        },
        mounted() {
            if(this.categoriesList.length === 0){
                this.loadCategoriesList();
            }
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
                let _this = this;
                let slug = '';

                data.routes.data.forEach(function (route) {
                    if(route.locale === _this.language) {
                        slug = route.slug;
                    }
                });

                return slug;
            },
            loadCategoriesList() {
                this.request.send('get', '/categories/all')
                    .then(response => {
                        this.categoriesList = response.data;
                        this.categoriesLoaded = true;
                    });
            },
            removeCategory() {
                let productID = this.product.id;
                let categoryID = this.deleteModalData.id;

                this.request.send('delete', '/products/'+productID+'/remove-category/'+categoryID)
                    .then(response => {
                        this.deleteModalOpen = false;
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: response.categoryName +' was successfully removed from this product.'
                        });
                    });
            },
            saveCategories() {
                let productID = this.product.id;
                let data = {'category-ids': selectedCategories};

                this.request.send('post', '/products/'+productID+'/categories',data)
                    .then(response => {
                        this.categoriesList = response.data;
                        this.categoriesLoaded = true;
                    });
            },
            selected(checked) {
                this.selectedCategories = checked;
            },
            closeAddModal() {
                this.addModalOpen = false;
            },
            openDeleteModal(category) {
                this.deleteModalData = {
                    'id': category.id,
                    'name': this.getAttribute(category, 'name'),
                    'slug': this.getRoute(category)
                };

                this.deleteModalOpen = true;
            },
            closeDeleteModal(category) {
                this.deleteModalData = {};
                this.deleteModalOpen = false;
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

                    <tr v-for="category in product.categories.data">
                        <td width="80">
                            <img src="/images/placeholder/no-image.svg" :alt="getAttribute(category, 'name')">
                        </td>
                        <td>
                            {{ getAttribute(category, 'name') }}
                        </td>
                        <td>
                            <input type="text" class="form-control" :value="getRoute(category)" disabled>
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(category)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!product.categories.length">
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
                    <span v-for="category in selectedCategories" class="badge">{{ category.name }}</span>
                </div>
                <hr>

                <candy-table tableClass="association-table" @checked="selected" :items="categoriesList" :search="search" :loaded="categoriesLoaded" :params="tableParams"></candy-table>

            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary">Add to Categories</button>
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