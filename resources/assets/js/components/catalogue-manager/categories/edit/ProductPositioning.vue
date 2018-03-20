<script>
    export default {
        data() {
            return {
                request: apiRequest,
                positioning: [],
                sortType: '',
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                }
            }
        },
        props: {
            categoryId: {
                type: String
            },
            sort: {
                type: String
            },
            products: {
                type: Array
            }
        },
        methods: {
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.positioning.splice(oldIndex, 1)[0];
                this.positioning.splice(newIndex, 0, movedItem);
                this.updatePositions();
                CandyEvent.$emit('notification', {
                    level: 'success'
                });
            },
            updatePositions() {
                let pos = 1;

                this.positioning.forEach(product => {
                    product.position = pos;
                    pos++;
                });

                this.save();
            },
            sortProducts() {
                if (this.sortType != 'custom') {
                    let sorts = this.sortType.split(':');
                    // console.log('object.);' + sorts[0]
                    this.positioning = _.orderBy(this.positioning, 'object.max_price', sorts[1]);
                }
                this.updatePositions();
            },
            remove(index) {
                this.positioning.splice(index, 1);
                this.updatePositions();
            },
            save() {
                let payload = {
                    sort_type: this.sortType
                };
                payload.products = _.map(this.positioning, item => {
                    return {
                        id: item.id,
                        position: item.position
                    }
                });
                this.request.send('PUT', '/categories/' + this.categoryId + '/products', payload)
                    .then(response => {
                    });
            }
        },
        mounted() {
            this.sortType = this.sort;

            this.positioning = _.map(this.products, product => {
                return {
                    id: product.id,
                    position: product.position,
                    object: product
                }
            });
        }
    }
</script>
<template>
    <div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="row">
                        <div class="col-md-9">
                            <h4>Product Positioning</h4>
                            <span class="text-warning">If product is unavailable for a customer, the next one in the order will be shown</span>
                        </div>
                        <div class="col-md-1 text-right">
                            <label style="margin-top:8px;">Sort type</label>
                        </div>
                        <div class="col-md-2">
                            <select v-model="sortType" class="form-control" @change="sortProducts">
                                <option value="min_price:asc">Price Low/High</option>
                                <option value="max_price:desc">Price High/Low</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info" v-if="sortType != 'custom'">
                        <strong><i class="fa fa-exclamation-circle"></i> Please note</strong>
                        <p>Ordering by price can be influenced by customer group pricing and so may be different from the order shown below. For complete control, choose <strong>custom</strong> from the sort type above</p>
                    </div>

                    <table class="table table-striped sortable">
                        <thead>
                            <tr>
                                <th v-if="sortType == 'custom'"></th>
                                <th>Product</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody  v-sortable="sortableOptions">
                            <tr v-for="(product, index) in positioning" :key="product.object.id">
                                <td class="handle" width="10%" v-if="sortType == 'custom'">
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
                                <td>{{ product.object|attribute('name') }}</td>
                                <td align="right">
                                    <button class="btn btn-sm btn-default btn-action" @click="remove(index)">
                                        <i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</template>