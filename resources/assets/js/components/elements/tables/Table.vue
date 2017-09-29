<script>
    export default {
        data() {
            return {
                channel: 'ecommerce',
                language: locale.current(),
                checked: []
            }
        },
        props: {
            loaded: false,
            items: {
                type: Array
            },
            search: '',
            params: {
                type: Object
            },
            tableClass: {
                type: String
            }
        },
        computed: {
            filteredItems:function()
            {
                var _self = this;
                return this.items.filter(function(item) {
                    return _self.getAttribute(item, 'name').toLowerCase().indexOf(_self.search.toLowerCase())>=0;
                });
            }
        },
        methods: {
            getAttribute: function(data, attribute) {
                return data.attribute_data[attribute][this.channel][this.language];
            },
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
            selected: function() {
                this.$emit('checked', this.checked);
            }
        }
    }
</script>

<template>
    <div>
        <table :class="['table', this.tableClass]">

            <colgroup>
                <col v-for="column in params.columns" :width="column.width"></col>
            </colgroup>

            <thead>
                <tr>
                    <th v-for="column in params.columns" :style="'text-align:'+column.align" :width="column.width">{{ column.name }}</th>
                </tr>
            </thead>

            <tbody v-if="loaded">

                <tr v-for="item in filteredItems">
                    <td v-for="column in params.columns" :align="column.align" :width="column.width">
                        <template v-if="column.type === 'attribute'">
                            {{ getAttribute(item, column.source) }}
                        </template>
                        <template v-if="column.type === 'image'">
                            <img :src="getImage('/images/placeholder/no-image.svg')" height='41'>
                        </template>
                        <template v-if="column.type === 'route'">
                            /{{ getRoute(item) }}/
                        </template>
                        <template v-if="column.type === 'checkbox'">
                            <div class="checkbox">
                                <input :id="item.id" type="checkbox" v-model="checked" :value="{'id':item.id, 'name': getAttribute(item, 'name')}" @change="selected(item.id)">
                                <label :for="item.id"><span class="check"></span></label>
                            </div>
                        </template>
                        <template v-else="column.type">
                            <a v-if="column.link" :href="params.linkUrl+'/'+item.id">
                                {{ item[column.source] }}
                            </a>
                            <span v-else="column.link">
                                {{ item[column.source] }}
                            </span>
                        </template>
                    </td>
                </tr>

            </tbody>

            <tbody v-else="loaded" class="text-center">
                <tr>
                    <td :colspan="params.columns.length" style="padding:40px;">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>