<!--
  Product Details
  This component is responsible for saving product details
 -->
<template>
    <div class="row">
        <div class="col-xs-12 col-md-11">
            <div class="form-inline">
              <div class="form-group">
                <label class="sr-only">Store Channels</label>
                <select class="form-control selectpicker">
                  <option data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                  <option data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                  <option data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                </select>
              </div>
              <div class="form-group">
                <label class="sr-only">Language</label>
                <select class="form-control selectpicker">
                  <option data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                  <option data-content="<span class='flag-icon flag-icon-fr'></span> French">French</option>
                  <option data-content="<span class='flag-icon flag-icon-de'></span> German">German</option>
                </select>
              </div>
              <button class="btn btn-default">Translate</button>
            </div>
            <hr>

            <div class="form-group" v-for="input in group.attributes.data">
                <label>
                  {{ input.name }}
                </label>
                <input type="text" class="form-control" :value="getValue(input.handle)">
                <input type="text" class="form-control" v-model="product.attribute_data[input.handle]['sv']" v-if="translating">
                <span class="text-danger" v-text="update.getError('attribute_data.' + input.handle + '.en')"></span>
            </div>
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