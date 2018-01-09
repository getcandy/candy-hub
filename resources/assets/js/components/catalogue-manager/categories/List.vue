
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
                    current_page: 1,
                    keywords: '',
                    includes: 'routes,assets'
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
                        {'name': 'Title', 'link': true, 'width': '*', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '100px', 'align': 'center', 'type': 'text', 'source': 'productCount'},
                        {'name': 'Availability', 'width': '200px', 'type': 'text', 'source': ''},
                        {'name': '', 'width': '200px', 'type': 'button', 'source': ''}
                    ],
                    linkUrl: 'categories'
                },
                tableParams: {
                    columns: [
                        {'name': '', 'link': true, 'width': '50px', 'type': 'image', 'source': 'asset'},
                        {'name': 'Title', 'link': true, 'width': '*', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '100px', 'align': 'center', 'type': 'text', 'source': 'product_count'},
                        {'name': 'Availability', 'width': '100px', 'type': 'text', 'source': ''},
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
                        this.params.total_pages = response.meta.pagination.total_pages;
                        this.meta = response.meta;
                        this.categoriesLoaded = true;
                    });
            },
            resetSearch() {
                this.params['keywords'] = null;
                this.keywords = '';
                this.params['filters'] = null;
                this.loadCategories();
            },
            createCategory() {
                let _this = this;

                // this.createModalData['attributes'] = [{
                //     'key': 'name',
                //     'value': this.category.name,
                //     'channel': this.channel,
                //     'locale': this.language
                // }];

                // this.createModalData['routes'] = [{
                //     'slug': this.category.name,
                //     'locale': this.language,
                //     'default': 1
                // }];

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
            <li role="presentation" v-for="(search, index) in savedSearches" :key="search.id" :class="{'active' : isActive(search)}">
                <a href="#" role="tab" data-toggle="tab" @click="applySavedSearch(search)">
                    {{ search.name }} <i class="fa fa-times" aria-hidden="true" @click="deleteSaved(index)"></i>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content section block">
            <div role="tabpanel" class="tab-pane active" id="all-products">

                <!-- Search Form -->
                <form>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">

                            <!--<button type="button" class="btn btn-default btn-full btn-pop-over">
                                Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i>
                            </button>-->    

                            <!-- Filter Pop Over 
                            <div class="pop-over">
                                <form>
                                    <label>Show all products where:</label>
                                    <div class="form-group">
                                        <select class="form-control selectpicker">
                                            <option>Display</option>
                                        </select>
                                    </div>
                                    <span class="form-link">
                                        is
                                    </span>
                                    <div class="form-group">
                                        <select class="form-control selectpicker">
                                            <option>Visible on Storefront</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-default">Add filter</button>
                                </form>
                            </div>-->

                        </div>
                        <div class="form-group col-xs-12 col-md-8">

                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-2">

                            <button type="submit" class="btn btn-default btn-full" @click="saveSearch()">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>

                        </div>
                    </div>
                </form>

                <!-- Applied Filter List -->
                <!-- <div class="filters">

                </div> -->

                <hr>

                <!-- Fancy Tree View -->
                <div id="tree-view" v-show="currentView === 'tree-view'">
                    <candy-fancytree sourceURL="/categories/" updateURL="/categories/" :reload="reloadList"
                                     :channel="channel" :language="language" :params="fancyParams">
                    </candy-fancytree>
                </div>

                <!-- List View -->
                <div id="list-view" v-show="currentView === 'list-view'">
                    <candy-table :items="categories" :loaded="categoriesLoaded" :params="tableParams"
                                    itemUrl="/catalogue-manager/categories/"
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
