
<script>
    import ListItem from './ListItem';

    export default {
        components: {
            ListItem
        },
        data() {
            return {
                categories: [],
                channel: this.$store.getters.getDefaultChannel.handle,
                language: locale.current(),
                request: apiRequest,
                meta: [],
                loaded: false,
                keywords: '',
                createModalOpen: false,
                params: {
                    type: 'category',
                    fields: 'name',
                    includes: 'routes',
                    per_page: 25,
                    page: 1,
                },
                createModalData: {
                    'attributes': [],
                    'routes': [],
                    'parent': {}
                },
            };
        },
        mounted() {
            this.loadCategories();

            CandyEvent.$on('category-added', event => {
                this.loadCategories();
            });
        },
        methods: {
            loadCategories() {
                this.loaded = false;
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
                    this.categories = categories;
                    this.loaded = true;
                });

            },
            resetSearch() {
                this.params['keywords'] = null;
                this.keywords = '';
            },
            searchCategories() {
                this.loaded = false;
                apiRequest.send('GET', 'search', [], this.params)
                    .then(response => {
                        this.categories = response.data;
                        this.params.total_pages = response.meta.last_page;
                        this.meta = response.meta;
                        this.loaded = true;
                    });
            },
            search: _.debounce(function (){
                    this.loaded = false;
                    this.editing = null;
                    this.params['keywords'] = this.keywords;

                    if (this.keywords) {
                        this.searchCategories();
                    } else {
                        this.loadCategories();
                    }
                }, 500
            ),
            reorder({newIndex, oldIndex}) {
                // Get the current one
                const moved = this.categories[oldIndex];
                const node = this.categories[newIndex];

                this.categories.splice(oldIndex, 1)[0];
                this.categories.splice(newIndex, 0, moved);

                let type = 'before';
                if (newIndex > oldIndex) {
                    type = 'after';
                }

                apiRequest.send('post', 'categories/reorder', {}, {
                    node: node.id,
                    'moved-node': moved.id,
                    action: type,
                }).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success',
                        message: 'Successfully Moved Category'
                    });
                });
            },
            createCategory() {
                // let _this = this;

                // this.request.send('post', '/categories', {
                //     'name' : {
                //         [this.language] : this.category.name
                //     },
                //     'url' : this.category.name.slugify(),
                //     'parent' : {
                //         'id' : this.createModalData.parent.id
                //     }
                // })
                //     .then(response => {
                //         _this.reloadTree();
                //         _this.closeCreateModal();
                //         CandyEvent.$emit('notification', {
                //             level: 'success',
                //             message: 'Category '+ _this.category.name +' Created'
                //         });
                //     });
            },
            closeCreateModal() {
                // this.createModalOpen = false;
                // this.category = {
                //     name: '',
                //     slug: '',
                // };
                // this.createModalData = {
                //     'attributes': [],
                //         'routes': [],
                //         'parent': {}
                // };
            },
        }
    };
</script>

<template>
    <div>

        <div class="panel section block">
            <div class="panel-body">
                <form v-on:submit.prevent>
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-10">
                            <div class="input-group input-group-full">
                                <span class="input-group-addon">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </span>
                                <label class="sr-only" for="search">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search" @keyup="search" v-model="keywords">
                            </div>
                        </div>
                        <div class="form-group col-xs-12 col-md-2">
                            <button type="button" class="btn btn-default btn-full" @click="saveSearch()">
                                <i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

                <template v-if="loaded">
                    <div v-sortable="{
                        handle: '.sorter',
                        group: 'root',
                        onEnd: this.reorder,
                        animation: 150,
                    }">
                        <list-item :sortable="categories.length > 1" :category="category" v-for="category in categories" :key="category.id"></list-item>
                                <!-- <div class -->
                            <!-- <ul class="list-group" v-sortable="{
                                handle: '.sorter',
                                group: 'root',
                                onEnd: this.reorder,
                                animation: 150,
                            }">
                                <list-item :sortable="categories.length > 1" :category="category" v-for="category in categories" :key="category.id"></list-item>
                            </ul> -->
                    </div>
                </template>
                <template v-else>
                    Loading
                </template>


        <!-- Create Category Modal -->


    </div>

</template>
