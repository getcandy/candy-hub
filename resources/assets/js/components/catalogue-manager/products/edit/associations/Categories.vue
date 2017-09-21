<template>
    <div class="row">
        <div class="col-xs-12 col-md-11">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h4>Categories</h4>
                </div>
                <div class="col-xs-12 col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">
                        Add Category
                    </button>
                </div>
            </div>
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>Category Name</td>
                    <td colspan="2">URL</td>
                </tr>
                </thead>
                <tbody>

                    <tr v-for="category in product.categories.data">
                        <td width="80">
                            <img src="/images/placeholder/no-image.svg" :alt="getAttribute(category, 'name')">
                        </td>
                        <td>
                            {{ getAttribute(category, 'name') }}
                        </td>
                        <td>
                            <input type="text" class="form-control" :value="getRoute(category)" disabled>
                        </td>
                        <td align="right">
                            <button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeCategory">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>

                    <tr v-if="product.categories.data.length === 0">
                        <td colspan="4">
                            <p class="empty">There are no categories associated with this product</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                channel: 'ecommerce',
                lang: locale.current()
            }
        },
        props: {
            product: {
                type: Object
            }
        },
        methods: {
            getAttribute: function (data, attribute) {
                return data.attribute_data[attribute][this.channel][this.lang];
            },
            getRoute: function (data) {

                let slug = '';

                data.routes.data.forEach(function (route) {
                    if(route.locale === this.lang) {
                        slug = route.slug;
                    }
                }.bind(this));

                return slug;
            }
        }
    }
</script>