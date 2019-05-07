<template>
    <div class="list-item-container">
        <div class="category-box">
            <div class="sorter">
                <figure v-if="sortable">
                    <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Artboard" fill="#ABABAB">
                                <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                            </g>
                        </g>
                    </svg>
                </figure>
            </div>
            <div class="panel category-panel">
                <div class="panel-body">
                    <header :class="{ expanded: this.loaded }">
                        <div class="row">
                            <div class="col-md-4">
                                <button class="load-btn" @click="toggleChildren(category.id)" :disabled="!category.children_count">
                                    <i class="fa" :class="{
                                        'fa-circle-o disabled': !this.loaded && !this.loading && !category.children_count,
                                        'fa-plus': !this.loaded && !this.loading && category.children_count,
                                        'fa-minus' : this.loaded && !this.loading && category.children_count,
                                        'fa-spin fa-sync' : this.loading,
                                    }"></i>
                                </button>
                                <figure class="thumbnail">
                                    <candy-thumbnail-loader :item="category"></candy-thumbnail-loader>
                                </figure>
                                <a :href="'categories/' + category.id" :title="category.name">
                                    <span v-if="category.name" v-html="category.name.trunc(50)">
                                    </span>
                                    <template v-else>
                                        {{ category|attribute('name') }}
                                    </template>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <small class="helper-label">SLUG</small><br>
                                <code><template v-if="getRoute(category).path">{{ getRoute(category).path }}/</template>{{ getRoute(category).slug }}</code>
                            </div>
                            <div class="col-md-1">
                                <small class="helper-label">Products</small><br>
                                {{ category.products_count }}
                            </div>
                            <div class="col-md-1">
                                <small class="helper-label">Children</small><br>
                                {{ category.children_count }}
                            </div>
                            <div class="col-md-2 text-right node-action-btns">
                                <button @click.prevent="addingChild = true" data-toggle="tooltip" title="Add Child" data-placement="top">
                                    <i class="fa fa-layer-group"></i>
                                </button>

                                <button @click="showMoveModal = true" data-toggle="tooltip" title="Move category" data-placement="top">
                                    <i class="fa fa-people-carry"></i>
                                </button>
                            </div>
                        </div>
                    </header>
                </div>
            </div>
        </div>
        <move-category :name="category.name" :id="category.id" :show="showMoveModal" @close="showMoveModal = false"></move-category>
        <div class="pushed">
            <div class="node-creator" v-if="addingChild">
                <div class="creator-input">
                    <label>Category Name</label>
                    <input type="text" v-model="newName">
                </div>
                <div class="creator-input">
                    <label>Category Slug</label>
                    <input type="text" v-model="newSlug" @keyup="editedSlug = true">
                </div>
                <div class="creator-btn">
                    <button class="btn btn-primary" @click="save">Save</button>
                    <button class="btn btn-grey" @click="cancel">Cancel</button>
                </div>
            </div>
            <div class="list-nodes">
                <div v-sortable="{
                    handle: '.sorter',
                    group: category.id,
                    animation: 150,
                    onEnd: this.reorder,
                }">
                    <list-item-row :sortable="children.length > 1" :category="child" v-for="child in children" :key="child.id"></list-item-row>
                </div>
                <template v-if="loaded && !children.length">
                    No Children Associated
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import MoveCategory from './MoveCategory';

    export default {
        name: 'list-item-row',
        components: {
            MoveCategory,
        },
        props: {
            sortable: {
                type: Boolean,
                default: false,
            },
            category: {
                type: Object,
                default() {
                    return {};
                },
            },
        },
        watch: {
            newName(value) {
                if (!this.editedSlug) {
                    this.newSlug = this.slugify(value);
                }
            }
        },
        mounted() {
            // this.children = this.category.children.data;
        },
        data() {
            return {
                newName: null,
                newSlug: null,
                editedSlug: false,
                children: [],
                loaded: false,
                loading: false,
                addingChild: false,
                showMoveModal: false,
            }
        },
        methods: {
            cancel() {
                this.newName = null;
                this.newSlug = null;
                this.addingChild = null;
            },
            reorder({newIndex, oldIndex}) {
                // Get the current one
                const moved = this.children[oldIndex];
                const node = this.children[newIndex];

                this.children.splice(oldIndex, 1)[0];
                this.children.splice(newIndex, 0, moved);

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
            slugify(text) {
                return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
            },
            getRoute(category) {
                return _.first(category.routes.data);
            },
            getPath(category) {

            },
            save() {
                apiRequest.send('POST', '/categories', {
                    name: {
                        en: this.newName,
                    },
                    description: {
                        en: null,
                    },
                    parent: {
                        id: this.category.id,
                    },
                    url: this.newSlug,
                    path: this.category.slug,
                }).then(response => {
                    CandyEvent.$emit('notification');
                    this.$emit('child');
                    this.cancel();
                })
            },
            toggleChildren(id) {
                if (this.loaded) {
                    this.children = [];
                    this.loaded = false;
                    return;
                }
                this.loaded = false;
                this.loading = true;
                apiRequest.send('get', '/categories/' + this.category.id + '/children', [], {
                    includes: ['routes', 'assets.transforms'],
                }).then(response => {
                    this.children = response.data;
                    this.loaded = true;
                    this.loading = false;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .node-action-btns {
        margin-top:.75em;
        button {
            border:1px solid #CFCFCF;
            padding:4px 6px;
            border-radius:4px;
            background:transparent;
            &:hover {
                background-color:#f5f5f5;
            }
        }
    }

    .thumbnail {
        max-width:50px;
        display:inline-block;
        margin-right:10px;
        border:none;
        margin-bottom:0;
    }
    i.disabled {
        color:#f5f5f5;
    }
    .node-creator {
        border:1px dashed #CFCFCF;
        padding:.875em;
        margin-top:1em;

        .creator-btn {
            width:19%;
            text-align: right;
            display:inline-block;
            button {
                margin-left: 2%;
            }
        }
    }
    .helper-label {
        font-size:.75em;
        display:inline-block;
        font-weight:600;
        margin-right:5px;
        color: #BBBBBB;
        text-transform:uppercase;
    }
    .list-item-container:hover {
        .category-panel {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    }

    .creator-input {
        width:35%;
        display: inline-block;
        margin-right:5%;
        label {
            font-size:.75em;
            text-transform:uppercase;
            font-weight:600;
            color:#7E7E7E;
        }
        input {
            width:100%;
            padding:.75em 10px;
            background-color:#DEDEDE;
            border:1px solid #CECECE;
        }
    }
    .list-item-container {
        margin-bottom:1em;
    }
    .category-box {
        display: flex;
        align-items: center;
        .sorter {
            margin-right:15px;
        }
        .panel {
            flex: 1;
            margin-bottom:0;
        }
    }
    .list-nodes {
        margin-top:1em;
    }
    .pushed {
        margin-left:30px;
    }
    figure.sorter {
        cursor:grab;
    }
    .load-btn {
        background-color:white;
        color:#b9b9b9;
        margin-right:8px;
        border:none;
    }
    header.expanded {
        > a {
            font-weight:600;
        }
    }
    .child-row {
        background-color:rgba(0,0,0,0.02)!important;
    }
</style>