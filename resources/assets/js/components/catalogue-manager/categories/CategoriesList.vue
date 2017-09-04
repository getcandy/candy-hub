<style>
    .nestable { width:100%; position: relative; display: block; margin: 0; padding: 0; list-style: none; font-weight: 200; font-size: 16px; line-height: 20px; }
    .nestable-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
    .nestable-list .nestable-list { padding-left: 50px; }
    .nestable-collapsed .nestable-list { display: none; }
    .nestable-item,
    .nestable-empty,
    .nestable-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 16px; line-height: 20px; }
    .nestable-handle {
        display: block;
        height: 65px;
        margin: 0;
        padding: 10px 10px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        border-bottom: 1px solid #eaeaea;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .nestable-icon {color:#ccc; margin-right:10px;}
    .nestable-image {margin-right:10px;}
    .nestable-handle:hover { color: #662d91; background: #fff; cursor: pointer; }
    .nestable-item > button { outline:none; display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 25px; margin: 20px 10px; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 16px; line-height: 1; text-align: center; }
    .nestable-item > button:before { content: '\f054'; font-family: FontAwesome; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
    .nestable-item > button[data-action="collapse"]:before { content: '\f078'; font-family: FontAwesome; }
    .nestable-placeholder,
    .nestable-empty >.nestable-item,
    .nestable-empty > .nestable-handle,
    .nestable-empty { margin: 0; padding: 0; min-height: 65px; background: #f8f8f8; border: 1px dashed #EAEAEA!important; box-sizing: border-box; -moz-box-sizing: border-box; }
    .nestable-dragel { position: absolute; pointer-events: none; z-index: 9999; }
    .nestable-dragel > .nestable-item .nestable-handle { margin-top: 0; }
</style>

<script>

    require('../../../nestable.js');

    export default {
        data() {
            return {
                categories: [],
                testCategories: [
                    {
                        "name": "category 1",
                        "id": 1,
                        "children": [{
                            "name": "category 1 child 1",
                            "id": 5,
                            "children": [{
                                "name": "category 1 child 1.1",
                                "id": 6,
                                "children": [{
                                    "name": "category 1 child 1.1.1",
                                    "id": 7
                                },
                                    {
                                        "name": "category 1 child 1.1.2",
                                        "id": 8
                                    }]
                            },
                                {
                                    "name": "category 1 child 2",
                                    "id": 9
                                },
                                {
                                    "name": "category 1 child 3",
                                    "id": 10
                                }
                                ]
                        }]
                    },
                    {
                        "id": 2,
                        "name": "category 2",
                        "children": [{
                            "id": 4,
                            "name": "category 2 child 1",
                            "children": [{
                                "id": 11,
                                "name": "category 2 child 1.1"
                            }]
                        }]
                    },
                    {
                        "name": "category 3 (No Children)",
                        "id": 3
                    }
                ]
            };
        },
        mounted() {

            let nestable = $('.nestable').nestable();
            this.loadCategories();
            let _self = this;

            $('.nestable').on('change', function(e) {

                console.log(nestable.nestable('serialize'));

                //_self.$set( _self, "categories", nestable.nestable('serialize'));
            });

        },
        methods: {
            loadCategories() {
                apiRequest.loadCategories(this.params)
                    .then(response => {
                        console.log(response.data.data);
                        this.categories = response.data.data;
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
                <a href="#all-products" aria-controls="all-products" role="tab" data-toggle="tab">
                    All Products
                </a>
            </li>
            <li role="presentation">
                <a href="#shoes" aria-controls="shoes" role="tab" data-toggle="tab">
                    Shoes <i class="fa fa-times" aria-hidden="true"></i>
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
                    <div class="filter active">Visible on Facebook
                        <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>

                <hr>
                <div style="display: inline-block; width:100%;">

                    <div class="nestable">
                        <ol class="nestable-list">
                            <candy-category v-for="category in categories" :category="category" :key="category.id"></candy-category>
                        </ol>
                    </div>

                </div>

            </div>
        </div>

    </div>
</template>