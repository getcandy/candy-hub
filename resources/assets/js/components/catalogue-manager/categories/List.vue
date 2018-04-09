
<script>

    export default {
        data() {
            return {
                categories: [],
                categoriesLoaded: false,
                savedSearches: [],
                currentView: 'tree-view',
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                request: apiRequest,
                meta: [],
                keywords: '',
                requestParams: {
                    per_page: 25,
                    keywords: '',
                    includes: 'routes,assets,channels'
                },
                params: {
                    type: 'category',
                    per_page: 25,
                    current_page: 1,
                    includes: 'routes,assets'
                },
                reloadList: false,
                createModalOpen: false,
                createModalData: {
                    'attributes': [],
                    'routes': [],
                    'parent': {}
                },
                category: {
                    name: '',
                    slug: '',
                },
                fancyParams: {
                    columns: [
                        {'name': 'Title', 'link': true, 'width': '10%', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '100px', 'align': 'center', 'type': 'text', 'source': 'products_count'},
                        {'name': '', 'width': '200px', 'type': 'button', 'source': ''}
                    ],
                    linkUrl: 'categories'
                },
                tableParams: {
                    columns: [
                        {'name': '', 'link': true, 'width': '5%', 'type': 'image', 'source': 'asset'},
                        {'name': 'Title', 'link': true, 'width': '25%', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '15%', 'align': 'center', 'type': 'text', 'source': 'products_count'},
                        {'name': 'Availability', 'width': '15%', 'type': 'availability', 'source': 'channels', 'field' : 'published_at'},
                        {'name': '', 'width': '200px', 'type': 'button', 'buttonName': 'Create Subcategory', 'icon': 'fa fa-plus'}
                    ],
                    linkUrl: 'category'
                }
            };
        },
        mounted() {

            let _this = this;

            $('#createCategoryModal').on('show.bs.modal', function (e) {
                _this.createModalData['parent'] = {'id': $(this).data('parent-id'), 'name': $(this).data('parent-name')};
                _this.createModalOpen = true;
                e.preventDefault();
            });

            $('.tab-pane').on('click', '.modal-button', function(){
                _this.createModalData['parent'] = {'id': $(this).data('parentId'), 'name': $(this).data('parentName')};
                _this.createModalOpen = true;
            });

            CandyEvent.$on('category-added', response => {
                this.reloadTree();
            });
        },
        watch: {
            currentView(value) {
                if(value === 'list-view' && !this.categoriesLoaded){
                    this.loadCategories();
                }
            }
        },
        methods: {
            loadCategories() {
                this.categoriesLoaded = false;
                this.request.send('get', '/categories',[], this.requestParams)
                    .then(response => {
                        this.categories = response.data;
                        this.requestParams.total_pages = response.meta.pagination.total_pages;
                        this.requestParams.current_page = response.meta.pagination.current_page;
                        this.categoriesLoaded = true;
                    });

            },
            slugify(value) {
                this.category.slug = value.slugify();
            },
            searchCategories() {
                this.categoriesLoaded = false;

                apiRequest.send('GET', 'search', [], this.params)
                    .then(response => {
                        this.categories = response.data;
                        this.requestParams.total_pages = response.meta.pagination.total_pages;
                        this.requestParams.current_page = response.meta.pagination.current_page;
                        this.meta = response.meta;
                        this.categoriesLoaded = true;
                    });
            },
            resetSearch() {
                this.requestParams['keywords'] = null;
                this.keywords = '';
                this.requestParams['filters'] = null;
                this.loadCategories();
            },
            createCategory() {
                let _this = this;

                this.request.send('post', '/categories', {
                    'name' : {
                        [this.language] : this.category.name
                    },
                    'url' : this.category.name.slugify(),
                    'parent' : {
                        'id' : this.createModalData.parent.id
                    }
                })
                    .then(response => {
                        _this.reloadTree();
                        _this.closeCreateModal();
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: 'Category '+ _this.category.name +' Created'
                        });
                    });
            },
            reloadTree() {
                this.reloadList = true;
                if (this.currentView === 'list-view') {
                    this.loadCategories();
                } else {
                    this.categoriesLoaded = false;
                }
            },
            saveSearch() {
                apiRequest.send('POST', '/saved-searches', {
                    type: 'category',
                    name: this.keywords,
                    keywords: this.keywords,
                    filters: this.filters
                }).then(response => {
                    this.savedSearches.push(response.data);
                });
            },
            search: _.debounce(function (){
                    this.params['keywords'] = this.keywords;
                    if (this.keywords) {
                        this.searchCategories();
                    } else {
                        this.loadCategories();
                    }
                }, 500
            ),
            closeCreateModal() {
                this.createModalOpen = false;
                this.category = {
                    name: '',
                    slug: '',
                };
                this.createModalData = {
                    'attributes': [],
                        'routes': [],
                        'parent': {}
                };
            },
            changePage(page) {
                this.loaded = false;
                this.requestParams.current_page = page;
                this.loadCategories();
            },
            filter(el) {
                this.requestParams.keywords = el.target.value;
                this.loadCategories();
            }
        }
    };
</script>

<template>
    <div>

        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#tree-view" aria-controls="tree-view" role="tab" data-toggle="tab" @click="currentView = 'tree-view'">
                    Tree View
                </a>
            </li>
            <li role="presentation">
                <a href="#list-view" aria-controls="list-view" role="tab" data-toggle="tab" @click="currentView = 'list-view'">
                    List View
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-products">

                <!-- Search Form -->
                <div class="row" v-if="currentView == 'list-view'">
                    <div class="col-md-12">
                        <form>
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>

                <!-- Fancy Tree View -->
                <div id="tree-view" v-show="currentView === 'tree-view'">
                    <candy-fancytree sourceURL="/categories/" updateURL="/categories/" :reload="reloadList"
                                     :channel="channel" :language="language" :params="fancyParams">
                    </candy-fancytree>
                </div>

                <!-- List View -->
                <div id="list-view" v-show="currentView === 'list-view'">
                    <candy-table :items="categories" :loaded="categoriesLoaded" :params="tableParams"
                                    itemUrl="/hub/catalogue-manager/categories/"
                                 :pagination="requestParams" @change="changePage">
                    </candy-table>
                </div>

            </div>
        </div>

        <!-- Create Category Modal -->
        <candy-modal id="createCategoryModal" title="Create Category" size="modal-md" v-show="createModalOpen" @closed="closeCreateModal()">

            <div slot="title">
                <h4 v-if="createModalData['parent'].name" class="modal-title">Create Sub Category <small>Under {{ createModalData['parent'].name }}</small></h4>
                <h4 v-else="" class="modal-title">Create Category</h4>
            </div>

            <div slot="body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" v-model="category.name" @input="slugify(category.name)">
                    <span class="text-danger" v-if="request.getError('attributes.0.value')" v-text="request.getError('attributes.0.value')"></span>
                </div>
                <div class="form-group">
                    <label for="slug">URL</label>
                    <input id="slug" type="text" class="form-control" v-model="category.slug" @change="slugify(category.slug)">
                    <span class="text-danger" v-if="request.getError('routes.0.slug')" v-text="request.getError('routes.0.slug')"></span>
                </div>
            </div>

            <div slot="footer">
                <button type="button" class="btn btn-primary" @click="createCategory()">Create Category</button>
            </div>

        </candy-modal>

    </div>

</template>
