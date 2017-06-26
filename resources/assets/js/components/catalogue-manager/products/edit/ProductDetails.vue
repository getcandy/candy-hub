<!--
  Product Details
  This component is responsible for saving product details
 -->
<template>
    <div>
        <div class="form-group" v-for="input in group.attributes.data">
            <label>
              {{ input.name }}
            </label>
            <input type="text" class="form-control" v-model="product.attribute_data[input.handle]['en']">
            <input type="text" class="form-control" v-model="product.attribute_data[input.handle]['sv']">
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: {
            group: {
                type: Object
            },
            product: {
                type: Object
            }
        },
        computed: {
        },
        methods: {
            save() {
                axios({
                  method: 'put',
                  url: '/api/v1/products/' + this.product.id,
                  data: this.product
                }).then(function (response) {
                  Event.$emit('notification', {
                    level: 'success'
                  });
                });
            }
        },
        mounted() {
            dispatcher.add('product-details', this);
            Event.$emit('tab-change', 'product-details');
        }
    }
</script>