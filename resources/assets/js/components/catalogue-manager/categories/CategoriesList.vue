
<script>

    export default {
        data() {
            return {
                categoriesList: [],
                categoriesLoaded: false,
                search: '',
                currentView: 'tree-view',
                channel: 'ecommerce',
                language: locale.current(),
                request: apiRequest,
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
                tableParams: {
                    columns: [
                        {'name': 'Title', 'width': '*', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '100px', 'align': 'center', 'type': 'text', 'source': 'productCount'},
                        {'name': 'Availability', 'width': '200px', 'type': 'text', 'source': ''},
                        {'name': '', 'width': '200px', 'type': 'button', 'source': ''}
                    ]
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
        },
        watch: {
            currentView(value) {
                if(value === 'list-view' && !this.categoriesLoaded){
                    this.loadCategoriesList();
                }
            },
            search() {
                if(this.currentView !== 'list-view'){
                    this.currentView = 'list-view';
                }
            }
        },
        methods: {
            loadCategoriesList() {
                apiRequest.send('get', '/categories/all')
                    .then(response => {
                        this.categoriesList = response.data;
                        this.categoriesLoaded = true;
                    });
            },
            slugify(value) {
                this.category.slug = value.slugify();
            },
            createCategory() {
                this.createModalData['attributes'] = [{
                    'key': 'name',
                    'value': this.category.name,
                    'channel': this.channel,
                    'locale': this.language
                }];

                this.createModalData['routes'] = [{
                    'slug': this.category.name,
                    'locale': this.language,
                    'default': 1
                }];

                this.request.send('post', '/categories/create', this.createModalData)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success',
                            message: 'Category '+ this.category.name +' Created'
                        });
                        this.reloadTree();
                        this.closeCreateModal();
                    }).catch(response => {
                        console.log(response);
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'There was an error creating your category'
                        });
                    });
            },
            reloadTree() {
                this.reloadList = true;
                if(this.currentView === 'list-view'){
                    this.loadCategoriesList();
                }else{
                    this.categoriesLoaded = false;
                }
            },
            closeCreateModal() {
                this.createModalOpen = false;
                this.category = {
                    name: '',
                    slug: '',
                };
                _this.modalData = {};
                this.request.clearError();
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
                    List All
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

                            <button type="button" class="btn btn-default btn-full btn-pop-over">
                                Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i>
                            </button>

                            <!-- Filter Pop Over -->
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
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-8">

                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                  <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" v-model="search" placeholder="Search">
                            </div>

                        </div>
                        <div class="form-group col-xs-12 col-md-2">

                            <button type="submit" class="btn btn-default btn-full" @click.prevent="loadProducts();">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>

                        </div>
                    </div>
                </form>

                <!-- Applied Filter List -->
                <div class="filters">

                </div>

                <hr>

                <!-- Fancy Tree View -->
                <div id="tree-view" v-show="currentView === 'tree-view'">
                    <candy-fancytree sourceURL="/categories/parent/" updateURL="/categories/" :reload="reloadList" :channel="channel" :language="language" :params="tableParams"></candy-fancytree>
                </div>

                <!-- List View -->
                <div id="list-view" v-show="currentView === 'list-view'">
                    <candy-table :items="categoriesList" :search="search" :loaded="categoriesLoaded" :params="tableParams"></candy-table>
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
