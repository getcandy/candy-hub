<template>
    <li class="list-group-item">
        <header :class="{ expanded: this.loaded }">
            <div class="row">
                <div class="col-md-6">
                    <figure class="sorter" v-if="sortable">
                        <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" fill="#D8D8D8">
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
                    <button class="load-btn" @click="toggleChildren(category.id)">
                        <i class="fa" :class="{
                            'fa-plus': !this.loaded && !this.loading,
                            'fa-minus' : this.loaded && !this.loading,
                            'fa-spin fa-refresh' : this.loading,
                        }"></i>
                    </button>
                    <a :href="'categories/' + category.id">
                        <template v-if="category.name">
                            {{ category.name }}
                        </template>
                        <template v-else>
                            {{ category|attribute('name') }}
                        </template>
                    </a>
                </div>

            </div>

        </header>
        <template v-if="loaded">
                <ul class="list-group" v-sortable="{
                    handle: '.sorter',
                    group: category.id,
                    animation: 150,
                }">
                <list-item-row :sortable="children.length > 1" :category="child" v-for="child in children" :key="child.id"></list-item-row>
            </ul>
        </template>
        <template v-if="loaded && !children.length">
            No Children Associated
        </template>
    </li>
</template>

<script>
    export default {
        name: 'list-item-row',
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
        mounted() {
            // this.children = this.category.children.data;
        },
        data() {
            return {
                children: [],
                loaded: false,
                loading: false,
            }
        },
        methods: {
            toggleChildren(id) {
                if (this.loaded) {
                    this.children = [];
                    this.loaded = false;
                    return;
                }
                this.loaded = false;
                this.loading = true;
                apiRequest.send('get', '/categories/' + this.category.id + '/children').then(response => {
                    this.children = response.data;
                    this.loaded = true;
                    this.loading = false;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    figure.sorter {
        display:inline-block;
        cursor:grab;
    }
    .load-btn {
        background-color:white;
        color:#b9b9b9;
        margin-right:8px;
        border:none;
    }
    header.expanded {
        margin-bottom:1em;
        > a {
            font-weight:600;
        }
    }
    .child-row {
        background-color:rgba(0,0,0,0.02)!important;
    }
</style>