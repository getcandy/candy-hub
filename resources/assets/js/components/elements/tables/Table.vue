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
            itemUrl: {
                type: String
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
            getUrl(id) {
                return this.itemUrl + id;
            },
            getAvailability(data, association, field) {

                let items = data[association] ? data[association].data : [];

                if (!items.length) {
                    return 'None';
                }

                let label = 'All',
                    visible = [];

                items.forEach(item => {
                    if (item.published_at) {
                        let now = moment();
                        let publish_date = moment(item.published_at);
                        if (!publish_date.isAfter(now)) {
                            visible.push(item.name);
                        }
                    } else if (item[field]) {
                        visible.push(item.name);
                    }
                });

                if (visible.length == items.length) {
                    return 'All';
                } else if (!visible.length) {
                    return 'None';
                }
                return visible.join(', ');
            },
            thumbnail(element) {
                if (element.thumbnail) {
                    return element.thumbnail.data.thumbnail;
                }
                return '/candy-hub/images/placeholder/no-image.svg';
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
                <th v-for="column in params.columns" :style="'text-align:'+column.align" :width="column.width">{{ column.name }}</th>
            </tr>
            </thead>

            <!-- Loaded with data -->
            <tbody v-if="loaded && items.length > 0">
                <tr class="clickable" v-for="item in items">
                    <!-- Dynamic Columns -->
                    <td v-for="column in params.columns" :align="column.align" :width="column.width">

                        <template v-if="column.type === 'attribute'">
                            <a :href="getUrl(item.id)">{{ item|attribute(column.source) }}</a>
                        </template>

                        <template v-else-if="column.type === 'route'">
                            /{{ getRoute(item) }}
                        </template>

                        <template v-else-if="column.type === 'image'">
                            <img :src="thumbnail(column)" height='41'>
                        </template>

                        <template v-else-if="column.type === 'button'">
                            <a :data-parent-id="item.id" :data-parent-name="item|attribute('name')" class="btn btn-default modal-button hover-btn">
                                <i :class="column.icon"></i> {{ column.buttonName }}
                            </a>
                        </template>

                        <template v-else-if="column.type == 'availability'">
                            {{ getAvailability(item, column.source, column.field) }}
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
                            <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>

        <div class="text-center" v-if="loaded">
            <candy-table-paginate :pagination="pagination" @change="changePage" v-if="loaded"></candy-table-paginate>
        </div>

    </div>
</template>