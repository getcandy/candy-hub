<script>

    require('../../../nestable.js');

    export default {
        data() {
            return {
                categories: [],
                lastMoved: []
            };
        },
        mounted() {

            let _candyThis = this;
            this.loadCategories();

            $('.nestable').on('change', function(e, node){
                _candyThis.save(node);
            });

        },
        methods: {
            loadCategories() {
                apiRequest.loadCategories(this.params)
                    .then(response => {

                        this.categories = response.data.data;

                        CandyEvent.$nextTick( function(){
                            $('.nestable').nestable();
                        });

                    });
            },
            save(node) {

                let el = $('.nestable li#'+ node.id);
                let parentID = el.parent().parent().attr('id');
                let siblings = [];

                el.parent().children().each( function(){
                    siblings.push($(this).attr("id"));
                });

                let data = {
                    "id": node.id,
                    "parent-id": parentID,
                    "siblings": siblings
                };

                apiRequest.send('post', '/categories/', data).then(response => {
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