<script>
    export default {
        data() {
            return {
                request: apiRequest,
                requestParams: {
                    per_page: 6,
                    current_page: 1,
                    keywords: '',
                    includes: 'routes',
                    type: 'category'
                },
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                addModalOpen: false,
                selectedCategories: [],
                deleteModalOpen: false,
                deleteModalData: {},
                search: '',
                categories: [],
                categoriesLoaded: false,
                productCategories: [],
                /**
                    Adding associations
                 */
                results: [],
                loading: false,
                meta: []
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
            thumbnail(product) {
              if (product.thumbnail) {
                  return product.thumbnail.data.thumbnail;
              }
              return '/candy-hub/images/placeholder/no-image.svg';
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
                        // this.requestParams.total_pages = response.meta.last_page;
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
                this.results = [];
                this.loading = true;
                this.requestParams.current_page = page;
                this.getResults(this.keywords);
            },
            save() {
                this.request.send('post', '/products/' + this.product.id + '/categories', {'categories': this.selectedCategories})
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.results = [];
                        this.addModalOpen = false;
                    });
            },
            closeAddModal() {
                this.save();
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
            assign(category) {
                this.selectedCategories.push(category.id);
                this.productCategories.push(category);
            },
            detatch(category) {
                this.selectedCategories.splice(this.selectedCategories.indexOf(category.id), 1);
                this.productCategories.splice(this.productCategories.indexOf(category), 1);
            },
            getResults() {
                this.requestParams.keywords = this.keywords;
                let results = this.request.send('GET', 'search', {}, this.requestParams).then(response => {
                    this.results = response.data;
                    this.requestParams.total_pages = response.meta.pagination.total_pages;
                    this.meta = response.meta;
                    this.loading = false;
                });
            },
            alreadyLinked(category) {
                return this.selectedCategories.contains(category.id);
            },
            updateKeywords: _.debounce(function (e) {
                this.results = [];
                this.loading = true;
                this.keywords = e.target.value;
                this.getResults();
            }, 500)
        } // end
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
                            <img src="/candy-hub/images/placeholder/no-image.svg" :alt="getAttribute(category, 'name')">
                        </td>
                        <td>
                            {{ getAttribute(category, 'name') }}
                        </td>
                        <td>
                            {{ getRoute(category) }}
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" @click="openDeleteModal(category)">
                                <fa icon="trash"></fa>
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
                    <input type="text" class="form-control search" v-model="search" placeholder="Search Categories" v-on:input="updateKeywords">
                </div>
                <hr>
                <table class="table association-table">
                    <thead>
                        <tr>
                            <th width="10%"></th>
                            <th width="40%">Name</th>
                            <th>Route</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot v-if="loading" class="text-center">
                        <tr>
                            <td colspan="25" style="padding:40px;">
                                <div class="loading">
                                    <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody class="list">
                        <tr v-for="category in results">
                            <td width="10%"><img :src="thumbnail(category)" :alt="category|attribute('name')" class="img-sm"></td>
                            <td class="name" width="40%">{{ category|attribute('name') }}</td>
                            <td>
                                {{ getRoute(category) }}
                            </td>
                            <td align="right">
                                <button @click="assign(category)" class="btn btn-sm btn-action btn-success" v-if="!alreadyLinked(category)">
                                    <fa icon="plus"></fa>
                                </button>
                                <button @click="detatch(category)" class="btn btn-sm btn-default btn-action" v-else>
                                    <fa icon="trash"></fa>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!loading && !results.length">
                            <td colspan="25">
                                <div class="alert alert-info">
                                    Start typing to see categories
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center">
                    <candy-table-paginate :pagination="requestParams" @change="changePage" v-if="!loading"></candy-table-paginate>
                </div>
                <!-- <candy-table :items="categories" :loaded="categoriesLoaded" @selected="addSelected"
                                :associations="true"
                             :params="tableParams" :pagination="requestParams" @change="changePage"
                             :checked="selectedCategories">
                </candy-table> -->

            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="save()">Save Assignments</button>
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