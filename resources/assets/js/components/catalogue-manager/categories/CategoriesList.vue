<style>
    .categories-list {display: inline-block; width:100%;}
    .nestable { width:100%; position: relative; display: block; margin: 0; padding: 0; list-style: none; font-weight: 200!important; font-size: 16px; line-height: 20px; }
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
                categories: []
            };
        },
        mounted() {

            this.loadCategories();
            //$('.nestable').on('change', this.updateOutput);

        },
        methods: {
            updateOutput() {
                //this.categories = $('.nestable').nestable('serialize');
                //this.save();
            },
            loadCategories() {
                apiRequest.loadCategories(this.params)
                    .then(response => {

                        this.categories = response.data.data;

                        CandyEvent.$nextTick( function(){
                            $('.nestable').nestable();
                        });

                    });
            },
            save() {
                apiRequest.send('post', '/categories/', this.categories).then(response => {
                    console.log(response);
                    CandyEvent.$emit('notification', {
                        level: 'success'
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
                <div class="categories-list">

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