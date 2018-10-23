<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="row">
                    <div class="col-md-9">
                        <h4>Category nodes</h4>
                        <span class="text-warning">
                            These are the direct descendants. To view further down the tree, select a category.
                        </span>
                    </div>
                </div>
                <hr>
                <table class="table table-striped sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Category</th>
                            <th>Children</th>
                            <th>Products</th>
                        </tr>
                    </thead>
                    <tbody  v-sortable="sortableOptions">
                        <tr v-for="item in nodes" :key="item.id">
                            <td class="handle" width="10%">
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
                            </td>
                            <td>
                                <a :href="url(item)">{{ item|attribute('name') }}</a>
                            </td>
                            <td>{{ item.children_count }}</td>
                            <td>{{ item.products_count }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            categoryId: {
                type: String,
                required: true,
            },
            nodes: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        data() {
            return {
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                }
            }
        },
        mounted() {

        },
        methods: {
            url(category) {
                return route('hub.categories.edit', category.id);
            },
            reorder ({oldIndex, newIndex}) {
                let action = 'after';

                const movedItem = this.nodes.splice(oldIndex, 1)[0];

                this.nodes.splice(newIndex, 0, movedItem);

                let sibling = this.nodes[newIndex - 1];

                if (!sibling) {
                    action = 'before';
                    sibling = this.nodes[newIndex + 1];
                }

                apiRequest.send('POST', '/categories/reorder', {
                    'moved-node' : movedItem.id,
                    'node': sibling.id,
                    'action' : action,
                })
                .then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                });
            },
            remove(ting) {

            }
        }
    }
</script>

<style scoped>

</style>