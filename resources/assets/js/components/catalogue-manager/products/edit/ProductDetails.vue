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
                  <option value="0" data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                  <option value="1" data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                  <option value="2" data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
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

                <div class="form-group">

                    <label :for="input.handle">{{ input.name }}</label>

                    <div class="form-group">
                        <candy-select v-model="attributes[input.handle]" :value="getValue(input.handle)" :options="input.lookups" :required="input.required" v-if="input.type == 'select'"></candy-select>
                        <candy-textarea v-model="attributes[input.handle]" :value="getValue(input.handle)" :required="input.required" v-else-if="input.type == 'textarea'"></candy-textarea>
                        <candy-input v-model="attributes[input.handle].ecommerce.en" :value="getValue(input.handle)" :required="input.required" v-else-if="input.type == 'text'"></candy-input>
                    </div>

                </div>

                <!--
                <label>
                  {{ input.name }}
                </label>

                <input type="text" class="form-control" :value="getValue(input.handle)">
                <input type="text" class="form-control" v-model="product.attribute_data[input.handle]['sv']" v-if="translating">
                <span class="text-danger" v-text="update.getError('attribute_data.' + input.handle + '.en')"></span>
                -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                update: apiRequest,
                translating: false,
                attributes: this.product.attribute_data
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
                //this.update.send('put', '/products/' + this.product.id, this.product);
                this.update.send('put', '/products/' + this.product.id, this.attributes);
            },
            getValue(handle, filter = 'ecommerce', language = 'en') {

                if(this.product.attribute_data[handle] && this.product.attribute_data[handle][filter] && this.product.attribute_data[handle][filter][language]){
                    return this.product.attribute_data[handle][filter][language];
                }else if (this.product.attribute_data[handle] && !this.product.attribute_data[handle][filter] && this.product.attribute_data[handle][language]){
                    return this.product.attribute_data[handle][language];
                }

            },
            cl(data) {
                // Temp delete just used for console log
                console.log(data);
            }
        },
        mounted() {
            Event.$emit('current-tab', this);
        }
    }
</script>