<style type="text/css">
    /* Define custom width and alignment of table columns */
    #treetable {
        table-layout: fixed;
        outline: none;
    }
    table.fancytree-ext-table tbody tr td {
        border: 0 solid #fff!important;
        border-bottom: 1px solid #ececee!important;
        height: 58px;
    }
    table tbody tr.fancytree-focused span.fancytree-title {
        outline: none!important;
    }
    .treetable #fancytree-drop-marker, .treetable span.fancytree-checkbox,
    .treetable span.fancytree-drag-helper-img, .treetable span.fancytree-empty,
    .treetable span.fancytree-expander, .treetable span.fancytree-icon, .treetable span.fancytree-vline {
        background-image: none!important;
    }
    .treetable span.fancytree-icon {
        margin-top: 12px!important;
        margin-right:15px;
    }
    .treetable span.fancytree-expander {
        margin-top: 14px!important;
        margin-right:15px;
    }
    .treetable span.fancytree-icon:before {
        font-family: FontAwesome;
        content:"\f047";
        color: #c1c2c3;
    }
    .treetable .table > thead > tr > th, .treetable .table.association-table .table > tbody > tr > th {
        border-bottom: 2px solid #ececee;
    }
    .treetable .table > tbody > tr .btn{
        display: none;
        background-color: #fff;
    }
    .treetable .table > tbody > tr .btn:hover {
        background-color: #f3f3f3;
    }
    .treetable .table > tbody > tr:hover .btn{
        display: inline-block;
    }
    .treetable .fancytree-ext-childcounter span.fancytree-childcounter,
    .treetable .fancytree-ext-filter span.fancytree-childcounter {
        color: #fff;
        background: rgb(102, 45, 145);
        border: 1px solid #662d91;
        position: absolute;
        top: -15px;
        right: -40px;
        min-width: 18px;
        height: 18px;
        line-height: 1;
        vertical-align: baseline;
        border-radius: 10px;
        padding: 0 4px;
        text-align: center;
        font-size: 12px;
        font-weight: bold;
    }
    table.fancytree-ext-table tbody tr:hover,
    table.fancytree-ext-table tbody tr.fancytree-active {
        background-color: #f5f5f5!important;
        outline: #70C0E7 solid 0!important;
    }
</style>

<script>

    import 'jquery.fancytree/dist/skin-win8/ui.fancytree.min.css';

    const fancytree = require('jquery.fancytree');

    require('jquery.fancytree/dist/modules/jquery.fancytree.dnd5');
    require('jquery.fancytree/dist/modules/jquery.fancytree.glyph');
    require('jquery.fancytree/dist/modules/jquery.fancytree.table');

    export default {
        data() {
            return {
                data: [],
                pagination: {
                    current_page: 1
                }
            };
        },
        props: {
            sourceURL: {
                type: String
            },
            updateURL: {
                type: String
            },
            channel: {
                type: String
            },
            language: {
                type: String
            },
            params: {
                type: Object
            },
            reload: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            this.loadData();
        },
        watch: {
            reload: function(value){
                if (value) {
                    this.reloadData();
                }
            }
        },
        methods: {
            initFancytable() {

                let glyph_opts = {
                    preset: "awesome4",
                    map: {
                        expanderClosed: "fa fa-chevron-right",
                        expanderLazy: "fa fa-chevron-right",
                        expanderOpen: "fa fa-chevron-down",
                        loading: "fa fa-spinner"
                    }
                };

                $("#treetable").fancytree({
                    extensions: ["dnd5", "glyph", "table"],
                    glyph: glyph_opts,
                    dnd5: {
                        scroll: false,
                        dragStart: function(node, data) {
                            return true;
                        },
                        dragDrag: function(node, data) {
                            data.dataTransfer.dropEffect = "move";
                        },
                        dragEnter: function(node, data) {
                            data.dataTransfer.dropEffect = "move";
                            return true;
                        },
                        dragOver: function(node, data) {
                            data.dataTransfer.dropEffect = "move";
                        },
                        dragDrop: function(node, data) {
                            node.setExpanded(true).always(function(){
                                // Wait until expand finished, then add the additional child
                                data.otherNode.moveTo(node, data.hitMode);
                                this.save(node.data.id, data.otherNode.data.id, data.hitMode);
                            }.bind(this));

                        }.bind(this)
                    },
                    source: this.data,
                    lazyLoad: function (event, data) {
                        let nodeID = data.node.data.id;
                        let request = new $.Deferred();
                        data.result = request.promise();

                        apiRequest.send('get', this.sourceURL + nodeID, [],  {
                                includes: 'children, assets'
                            })
                            .then(response => {
                                request.resolve(response.data.children.data);
                            })
                            .catch(error => {
                                request.reject(new Error("Could not load data"));
                            });
                    }.bind(this),
                    renderTitle: function(event, data){
                        let node = data.node;
                        node.title = '<a href="'+ this.params.linkUrl +'/'+ data.node.data.id +'">'+ this.getImage(node.data)+ this.getAttribute(node.data, 'name') +'</a>';
                    }.bind(this),
                    table: {
                        nodeColumnIdx: 0
                    },
                    renderColumns: function(event, data) {

                        let node = data.node,
                            $tdList = $(node.tr).find(">td");

                        this.params.columns.forEach((column, index) => {
                            // Skip first as that will always be the title
                            if(index > 0){
                                if(column.type === 'button'){
                                    $tdList.eq(index).html(this.createNewButton(node.data.id, this.getAttribute(node.data, 'name')));
                                }else if(column.type === 'image'){
                                    $tdList.eq(index).html(this.getImage(node.data[column.source]));
                                }else{
                                    $tdList.eq(index)[column.type](node.data[column.source]);
                                }
                            }
                        });
                    }.bind(this)
                });
            },
            createNewButton: function(parentID, parentName) {
                return '<a data-parent-id="'+ parentID +'" data-parent-name="'+ parentName +'" class="btn btn-default modal-button"><i class="fa fa-plus"></i> Create Subcategory</a>';
            },
            getImage: function(data) {
                let url = '/images/placeholder/no-image.svg';
                if (data.thumbnail) {
                    url = data.thumbnail.data.thumbnail;
                }
                return '<img class="fancytree-image" src="' + url + '" >';
            },
            getAttribute: function(data, attribute) {
                return data.attribute_data[attribute][this.channel][this.language];
            },
            reloadData: function() {
                apiRequest.send('get', this.sourceURL, [], {
                    per_page: 15,
                    current_page: this.pagination.current_page
                })
                .then(response => {
                    this.data = response.data;
                    this.pagination = response.meta.pagination;

                    var tree = $('#treetable').fancytree('getTree');
                    tree.reload(this.data);
                });
            },
            loadData: function() {
                apiRequest.send('get', this.sourceURL, [], {
                    per_page: 15,
                    current_page: this.pagination.current_page
                })
                .then(response => {
                    this.data = response.data;
                    this.pagination = response.meta.pagination;
                    CandyEvent.$nextTick( function(){
                        this.initFancytable();
                    }.bind(this));
                });
            },
            save(node, movedNode, action) {
                let data = {
                    'node': node,
                    'moved-node': movedNode,
                    'action': action
                };
                apiRequest.send('post', this.updateURL +'reorder', data).then(response => {
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
            },
            changePage(page) {
                this.pagination.current_page = page;
                this.reloadData();
            }

        }
    }
</script>

<template>
    <div class="treetable">

        <table id="treetable" class="table table-hover fancytree-fade-expander">
            <colgroup>
                <col v-for="column in params.columns" :width="column.width"></col>
            </colgroup>
            <thead>
                <tr>
                    <th v-for="column in params.columns" :style="'text-align:'+column.align">
                        {{ column.name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td v-for="column in params.columns" :align="column.align">
                        <a v-if="column.link">

                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <candy-table-paginate :pagination="pagination" @change="changePage"></candy-table-paginate>
        </div>


    </div>
</template>