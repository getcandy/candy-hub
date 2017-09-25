<script>
    export default {
        data() {
            return {
                channel: 'ecommerce',
                language: locale.current(),
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
            getImage: function(data) {
                return '<img class="fancytree-image" src="/images/placeholder/no-image.svg" height="41">';
            }
        }
    }
</script>

<template>
    <div>
        <table class="table table-hover">

            <colgroup>
                <col v-for="column in params.columns" :width="column.width"></col>
            </colgroup>

            <thead>
                <tr>
                    <th v-for="column in params.columns" :style="'text-align:'+column.align">{{ column.name }}</th>
                </tr>
            </thead>

            <tbody v-if="loaded">

                <tr v-for="item in filteredItems">
                    <td v-for="column in params.columns" :align="column.align">
                        <div v-if="column.type === 'attribute'">
                            {{ getAttribute(item, column.source) }}
                        </div>
                        <div v-if="column.type === 'image'">
                            {{ getImage(item[column.source]) }}
                        </div>
                        <div v-else="">
                            {{ item[column.source] }}
                        </div>

                    </td>
                </tr>

            </tbody>

            <tbody v-else="loaded" class="text-center">
                <tr>
                    <td colspan="6" style="padding:40px;">
                        <div class="loading">
                            <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>