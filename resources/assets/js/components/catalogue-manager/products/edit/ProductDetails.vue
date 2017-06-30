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
            <input type="text" class="form-control" :value="getValue(input.handle)">
            <input type="text" class="form-control" v-model="product.attribute_data[input.handle]['sv']" v-if="translating">
            <span class="text-danger" v-text="update.getError('attribute_data.' + input.handle + '.en')"></span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                update: apiRequest,
                translating: false
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
        methods: {
            save() {
                this.update.send('put', '/products/' + this.product.id, this.product);
            },
            getValue(handle) {
                if (!this.product.attribute_data[handle]) {
                    this.product.attribute_data[handle] = {
                        ecommerce : {
                            en: '',
                            sv: ''
                        },
                        mobile : {
                            en: '',
                            sv: ''
                        },
                        print : {
                            en: '',
                            sv: ''
                        }
                    };
                }
                return this.product.attribute_data[handle]['ecommerce']['en'];
            }
        },
        mounted() {
            console.log(this.product.attribute_data);
            Event.$emit('current-tab', this);
        }
    }
</script>