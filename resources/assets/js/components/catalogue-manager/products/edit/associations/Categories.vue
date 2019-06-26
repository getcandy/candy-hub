<script>
    import ListItem from '../../../categories/ListItem';
    export default {
        components : {
            ListItem
        },
        data() {
            return {
                request: apiRequest,
                requestParams: {
                    total_pages: 0,
                    per_page: 6,
                    page: 1,
                    keywords: '',
                    includes: 'routes,assets.transforms',
                    type: 'category'
                },
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                addModalOpen: false,
                selectedCategories: [],
                deleteModalOpen: false,
                deleteModalData: {},
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
                if (!data.attribute_data) {
                    return data[attribute];
                }
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
                this.categoriesLoaded = false;
                this.request.send('get', '/categories',[], {
                    tree: true,
                    includes: ['routes', 'assets.transforms'],
                    depth: 0,
                })
                .then(response => {
                    let categories = response.data;
                    _.each(categories, category => {
                        category.expanded = false;
                        category.loaded = false;
                    });
                    this.results = categories;
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
            alreadyLinked(category) {
                return this.selectedCategories.contains(category.id);
            }
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
                    <tr v-for="category in productCategories" :key="category.id">
                        <td width="80">
                            <candy-thumbnail-loader :item="category"></candy-thumbnail-loader>
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
                <template v-if="categoriesLoaded">
                    <list-item theme="compact" :selected="selectedCategories" :associatable="true" @associate="assign" @disassociate="detatch" @child="loadCategories" :sortable="categories.length > 1" :category="category" v-for="category in results" :key="category.id"></list-item>
                </template>
                <template v-else>
                    <fa icon="sync" spin />
                </template>
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