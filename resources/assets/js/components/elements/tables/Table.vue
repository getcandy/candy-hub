<script>
    export default {
        props: {
            items: {
                type: Array
            },
            search: ''
        },
        computed: {
            filteredItems:function()
            {
                var self=this;
                return this.items.filter(function(item) {
                    return item.attribute_data.name.ecommerce.en.toLowerCase().indexOf(self.search.toLowerCase())>=0;
                });
            }
        }
    }
</script>

<template>
    <div>
        <table id="listtable" class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in filteredItems">
                    <td>
                        {{ item.attribute_data.name.ecommerce.en }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>