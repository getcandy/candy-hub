<script>
    export default {
        data() {
            return {
                language: locale.current(),
                selectAll: false,
                checkedCount: 0,
                selected: []
            }
        },
        props: {
            loaded: false,
            checked: {
                type: Array,
                default() {
                    return [];
                }
            },
            items: {
                type: Array
            },
            params: {
                type: Object
            },
            tableClass: {
                type: String
            },
            pagination: {}
        },
        watch: {
            selected: function(val) {
                this.checkedCount = val.length;
                this.selectAll = (val.length === this.items.length);
                this.$emit('selected', val);
            },
            selectAll: function(val) {
                let selected = [];

                if (val) {
                    this.items.forEach(function (item) {
                        selected.push(item.id);
                    });
                }
                this.selected = selected;
            }
        },
        mounted() {
            this.checked.forEach(val => {
                this.selected.push(val);
            });
        },
        methods: {
            getRoute: function (data) {

                let slug = '';

                data.routes.data.forEach(function (route) {
                    if(route.locale === this.language) {
                        slug = route.slug;
                    }
                }.bind(this));

                return slug;
            },
            getImage: function(url) {
                // TODO
                return url;
            },
            selectAllClick() {
                this.selectAll = !this.selectAll;
            },
            changePage(page) {
                this.$emit('change', page);
            }
        }
    }
</script>

<template>
    <div>

        <table :class="['table', 'table-striped', this.tableClass]">

            <colgroup>
                <col width="10px"></col>
                <col v-for="column in params.columns" :width="column.width"></col>
            </colgroup>

            <thead>
            <tr>
                <th width="6%">
                    <div class="checkbox bulk-options" :class="{'active': (selectAll || checkedCount > 0)}">
                        <input v-model="selectAll" type="checkbox" class="select-all">
                        <label @click="selectAllClick"><span class="check"></span></label>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>

                        <div class="bulk-actions">
                            <div class="border-inner">
                                {{ checkedCount }} item(s) selected
                                <a href="#" class="btn btn-outline btn-sm">Edit</a>
                                <a href="#" class="btn btn-outline btn-sm">Publish</a>
                                <a href="#" class="btn btn-outline btn-sm">Hide</a>
                                <a href="#" class="btn btn-outline btn-sm">Delete</a>
                            </div>
                            <div v-if="checkedCount == items.length" class="all-selected">
                                <em>All items on this page are selected</em>
                            </div>
                        </div>
                    </div>
                </th>
                <th v-for="column in params.columns" :style="'text-align:'+column.align" :width="column.width">{{ column.name }}</th>
            </tr>
            </thead>

            <!-- Loaded with data -->
            <tbody v-if="loaded && items.length > 0">
                <tr class="clickable" v-for="item in items">

                    <!-- Default Columns -->
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" :id="'item' + item.id" :value="item.id" v-model="selected">
                            <label :for="'item' + item.id"><span class="check"></span></label>
                        </div>
                    </td>

                    <!-- Dynamic Columns -->
                    <td v-for="column in params.columns" :align="column.align" :width="column.width">

                        <template v-if="column.type === 'attribute'">
                            {{ item|attribute(column.source) }}
                        </template>

                        <template v-else-if="column.type === 'route'">
                            /{{ getRoute(item) }}
                        </template>

                        <template v-else-if="column.type === 'image'">
                            <img :src="getImage('/images/placeholder/no-image.svg')" height='41'>
                        </template>

                        <template v-else-if="column.type === 'button'">
                            <a :data-parent-id="item.id" :data-parent-name="item|attribute('name')" class="btn btn-default modal-button hover-btn">
                                <i :class="column.icon"></i> {{ column.buttonName }}
                            </a>
                        </template>

                        <template v-else>
                            <a v-if="column.link" :href="params.linkUrl+'/'+item.id">
                                {{ item[column.source] }}
                            </a>
                            <span v-else>
                                {{ item[column.source] }}
                            </span>
                        </template>

                    </td>

                </tr>
            </tbody>

            <!-- Loaded No Data -->
            <tbody class="text-center" v-else-if="loaded && items.length === 0">
                <tr>
                    <td :colspan="params.columns.length+1" style="padding:40px;">
                        No items found
                    </td>
                </tr>
            </tbody>

            <!-- loading -->
            <tbody class="text-center" v-else>
                <tr>
                    <td :colspan="params.columns.length+1" style="padding:40px;">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>

        <div class="text-center">
            <candy-table-paginate :pagination="pagination" @change="changePage" v-if="loaded"></candy-table-paginate>
        </div>

    </div>
</template>