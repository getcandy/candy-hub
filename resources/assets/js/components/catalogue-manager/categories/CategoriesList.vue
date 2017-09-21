<style type="text/css">
    /* Define custom width and alignment of table columns */
    #treetable {
        table-layout: fixed;
        outline: none;
    }
    .categories-list table.fancytree-ext-table tbody tr td {
        border: 0 solid #fff!important;
        border-bottom: 1px solid #ececee!important;
        height: 58px;
    }
    .categories-list table tbody tr.fancytree-focused span.fancytree-title {
        outline: none!important;
    }
    .categories-list #fancytree-drop-marker, .categories-list span.fancytree-checkbox,
    .categories-list span.fancytree-drag-helper-img, .categories-list span.fancytree-empty,
    .categories-list span.fancytree-expander, .categories-list span.fancytree-icon, .categories-list span.fancytree-vline {
        background-image: none!important;
    }
    .categories-list span.fancytree-icon {
        margin-top: 12px!important;
        margin-right:15px;
    }
    .categories-list span.fancytree-expander {
        margin-top: 14px!important;
        margin-right:15px;
    }
    .categories-list span.fancytree-icon:before{
        font-family: FontAwesome;
        content:"\f047";
        color: #c1c2c3;
    }
    .categories-list .table > thead > tr > th, .categories-list .table.association-table .table > tbody > tr > th {
        border-bottom: 2px solid #ececee;
    }
    .categories-list .table > tbody > tr .btn{
        display: none;
        background-color: #fff;
    }
    .categories-list .table > tbody > tr .btn:hover {
        background-color: #f3f3f3;
    }
    .categories-list .table > tbody > tr:hover .btn{
        display: inline-block;
    }
</style>
<script>

    import fancytree from 'jquery.fancytree/dist/jquery.fancytree-all-deps.min';
    import 'jquery.fancytree/dist/src/jquery.fancytree.dnd5.js';
    import 'jquery.fancytree/dist/src/jquery.fancytree.edit.js';
    import 'jquery.fancytree/dist/src/jquery.fancytree.glyph.js';
    import 'jquery.fancytree/dist/src/jquery.fancytree.table.js';
    import 'jquery.fancytree/dist/skin-win8/ui.fancytree.min.css';

    export default {
        data() {
            return {
                categories: [],
                modalParentID: '',
                channel: 'ecommerce',
                lang: locale.current()
            };
        },
        mounted() {
            this.loadCategories();
        },
        methods: {
            initFancytable() {

                let glyph_opts = {
                    preset: "bootstrap3",
                    map: {
                        expanderClosed: "fa fa-chevron-right",  // glyphicon-plus-sign
                        expanderLazy: "fa fa-chevron-right",  // glyphicon-plus-sign
                        expanderOpen: "fa fa-chevron-down"  // glyphicon-minus-sign
                    }
                };

                $("#treetable").fancytree({
                    extensions: ["dnd5", "glyph", "table"],
                    glyph: glyph_opts,
                    dnd5: {
                        scroll: false,

                        // --- Drag-support:
                        dragStart: function(node, data) {
                            return true;
                        },
                        dragLeave: function(node, data) {
                        },
                        dragDrop: function(node, data) {
                            node.setExpanded(true).always(function(){
                                // Wait until expand finished, then add the additional child
                                data.otherNode.moveTo(node, data.hitMode);
                                this.save(node.data.id, data.otherNode.data.id, data.hitMode);
                            }.bind(this));

                        }.bind(this)
                    },
                    source: this.categories,
                    lazyLoad: function(event, data){
                        let nodeID = data.node.data.id;
                        let dfd = new $.Deferred();

                        data.result = dfd.promise();

                        apiRequest.loadCategories(nodeID)
                            .then(response => {
                                dfd.resolve(response.data.data);
                            })
                            .catch(error => {
                                dfd.reject(new Error("TEST ERROR"));
                            });
                    },
                    renderTitle: function(event, data){
                        let node = data.node;
                        node.title = this.getThumbnail()+ this.getAttribute(node.data, 'name');
                    }.bind(this),
                    table: {
                        nodeColumnIdx: 0
                    },
                    renderColumns: function(event, data) {


                        let node = data.node,
                            $tdList = $(node.tr).find(">td");

                        $tdList.eq(1).text(node.data.productCount);
                        $tdList.eq(3).html(this.createNewButton(node.data.id));
                        //$tdList.eq(4).text(this.getAttribute(node.data, 'name'));
                    }.bind(this)
                });
            },
            loadCategories(parentID) {
                apiRequest.loadCategories(parentID)
                    .then(response => {
                        this.categories = response.data.data;

                        CandyEvent.$nextTick( function(){
                            this.initFancytable();
                        }.bind(this));
                    });
            },
            createNewButton: function(parentID) {
                return '<a data-toggle="modal" data-target="#createCategoryModal" class="btn btn-default" @click="setModalParentID('+parentID+')"><i class="fa fa-plus"></i> Create Subcategory</a>';
            },
            setModalParentID: function() {
                this.modalParentID = window.modalParentID;
            },
            refreshList: function(categories) {
                this.modalParentID = '';
                this.categories = categories.data;
            },
            getAttribute: function(data, attribute) {
                return data.attribute_data[attribute][this.channel][this.lang];
            },
            getThumbnail: function(data) {
                return '<img class="dd-image" src="/images/placeholder/no-image.svg" height="41">';
            },
            save(node, movedNode, action) {
                console.log(node);console.log(movedNode);console.log(action);
                let data = {
                    'node': node,
                    'moved-node': movedNode,
                    'action': action
                };

                apiRequest.send('post', '/categories/reorder', data).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Successfully Moved Category'
                    });
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
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
                <a href="#all-products" aria-controls="all-categories" role="tab" data-toggle="tab">
                    All Categories
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
                                <input type="text" class="form-control" id="search" placeholder="Search">
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
                    <div class="filter active">Visible on Storefront
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                    <div class="filter active">Visible onref Facebook
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>

                <hr>

                <div class="categories-list">

                    <table id="treetable" class="table table-hover fancytree-fade-expander">
                        <colgroup>
                            <col width="*"></col>
                            <col width="100px"></col>
                            <col width="100px"></col>
                            <col width="200px"></col>
                        </colgroup>
                        <thead>
                        <tr> <th>Title</th> <th class="text-center">Products</th> <th class="text-center">Availability</th> <th></th> </tr>
                        </thead>
                        <tbody>
                        <tr> <td></td> <td class="text-center"></td> <td></td> <td></td> </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

        <candy-categories-modals :parentID="modalParentID" @categoryCreated="refreshList"></candy-categories-modals>

    </div>

</template>
