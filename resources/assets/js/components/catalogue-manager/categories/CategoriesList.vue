
<script>

    export default {
        data() {
            return {
                categoriesList: [],
                categoriesLoaded: false,
                currentView: 'tree-view',
                modalData: {},
                channel: 'ecommerce',
                language: locale.current(),
                tableParams: {
                    columns: [
                        {'name': 'Title', 'width': '*', 'type': 'attribute', 'source': 'name'},
                        {'name': 'Products', 'width': '100px', 'align': 'center', 'type': 'text', 'source': 'productCount'},
                        {'name': 'Availability', 'width': '200px', 'type': 'text', 'source': ''},
                        {'name': '', 'width': '200px', 'type': 'button', 'source': ''}
                    ]
                },
                reloadList: false,
                search: ''
            };
        },
        mounted() {

            let _this = this;

            $('.tab-pane').on('click', '.modal-button', function(){
                _this.modalData = {'id': $(this).data('parent-id'), 'name': $(this).data('parent-name')};
                $('#createCategoryModal').modal('show');
            });

            $('#createCategoryModal').on('hidden.bs.modal', function () {
                _this.modalData = {};
            });
        },
        watch: {
            currentView: function(value) {
                if(value === 'list-view' && !this.categoriesLoaded){
                    this.loadCategoriesList();
                }
            },
            search: function() {
                if(this.currentView !== 'list-view'){
                    this.currentView = 'list-view';
                }
            }
        },
        methods: {
            reloadTree() {
                this.reloadList = true;
                if(this.currentView === 'list-view'){
                    this.loadCategoriesList();
                }else{
                    this.categoriesLoaded = false;
                }
            },
            loadCategoriesList() {
                apiRequest.send('get', '/categories/all')
                    .then(response => {
                        this.categoriesList = response.data;
                        this.categoriesLoaded = true;
                    });
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

                <!-- Filter List -->
                <div class="filters">

                </div>

                <hr>

                <div id="tree-view" v-show="currentView === 'tree-view'">
                    <candy-fancytree sourceURL="/categories/parent/" updateURL="/categories/" :reload="reloadList" :channel="channel" :language="language" :params="tableParams"></candy-fancytree>
                </div>

                <div id="list-view" v-show="currentView === 'list-view'">
                    <candy-table-list :items="categoriesList" :search="search" :loaded="categoriesLoaded" :params="tableParams"></candy-table-list>
                </div>

            </div>
        </div>

        <candy-categories-modals :modalData="modalData" @categoryCreated="reloadTree()"></candy-categories-modals>

    </div>

</template>
