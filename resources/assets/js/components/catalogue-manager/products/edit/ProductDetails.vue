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
            <span class="text-danger" v-text="update.getError('attribute_data.' + input.handle + '.en')"></span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                update: apiRequest,
                errors: []
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
                this.update.send('put', '/products/' + this.product.id, this.product);
            }
        },
        mounted() {
            Event.$emit('current-tab', this);
        }
    }
</script>