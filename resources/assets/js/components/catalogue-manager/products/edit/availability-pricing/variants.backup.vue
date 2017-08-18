<script>
    export default {
        data() {
            return {
                current: {},
                currentIndex: 0,
                createVariant: false
            }
        },
        props: {
            product: {
                type: Object
            },
            variants: {
                type: Array
            }
        },
        created() {
            this.current = this.variants[0];
        },
        methods: {
            selectVariant(index) {
                this.current = this.variants[index];
                this.currentIndex = index;
            },
            deleteVariant(index) {
                if (confirm('Are you sure you want to delete this variant?')) {
                    apiRequest.send('delete', '/products/variants/' + this.variants[index].id)
                        .then(response => {
                            CandyEvent.$emit('notification', {
                                level: 'success'
                            });
                            this.variants.splice(index, 1);
                            this.current = this.variants[0];
                        }).catch(response => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'An error occurred, please refresh and try again'
                        });
                    });
                }
            },
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            convertToCm(measurement) {
                let rate = 1;
                if (measurement.unit == 'mm') {
                    rate = 0.1;
                } else if (measurement.unit == 'in') {
                    rate = 2.54;
                }
                return measurement.value * rate;
            }
        },
        computed: {
        //     volume() {
        //         // Convert height to cm...
        //         let height = this.convertToCm(this.current.height),
        //             width = this.convertToCm(this.current.width),
        //             depth = this.convertToCm(this.current.depth),
        //             cmsquared = height * width * depth;

        //         if (this.current.volume.unit == 'l') {
        //             return cmsquared / 1000;
        //         }
        //         return cmsquared;
        //     }
        // }
    }
</script>

<template>
  <div>
    <div class="row">
      <div class="col-xs-12 col-md-11">
        <div class="row">
          <div class="col-xs-12">
            <div class="row">
              <div class="col-md-8">
                <h4>Product Availability</h4>
              </div>
              <div class="col-md-4 text-right">
                <candy-create-variant :product="product" :showModal="createVariant"></candy-create-variant>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="col-md-12">
        </div>
        <div class="row">
          <div class="col-xs-12" :class="{'col-md-8 col-md-push-4': variants.length > 1}">
            <h4>Options</h4>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-md-8">
                <template v-for="(value, label, index) in current.options">
                  <div class="form-group">
                    <label>{{ capitalize(label) }}</label>
                    <input type="text" class="form-control" v-model="current.options[label]">
                  </div>
                </template>
              </div>
              <div class="col-xs-12 col-md-4">
                <a href="" class="variant-option-img">
                  <div class="change-img">
                    <img src="/images/placeholder/no-image.svg" alt="Placeholder" class="placeholder">
                    Change image
                  </div>
                </a>
                <!--
                <a href="" class="variant-option-img">

                </a>
                <a href="#">Change image</a>-->
              </div>
            </div>

            <h4>Pricing</h4>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>Price</label>
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">&pound;</span>
                    <input type="number" class="form-control" v-model="current.price">
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>Compare at Price</label>
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">&pound;</span>
                    <input type="number" class="form-control">
                  </div>
                </div>
              </div>
              <div class="col-xs-6 col-md-2">
                <div class="form-group">
                  <label>Tax</label>
                  <candy-select :options="['0%','5%','20%']"></candy-select>
                </div>
              </div>
            </div>

            <h4>Inventory</h4>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>Inventory Policy</label>
                  <candy-select :options="['Option 1','Option 2','Option 3']"></candy-select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6 col-md-5">
                <div class="form-group">
                  <label>SKU</label>
                  <input type="text" class="form-control" v-model="current.sku">
                </div>
              </div>
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>Quantity</label>
                  <input type="number" class="form-control" v-model="current.inventory">
                </div>
              </div>
              <div class="col-xs-12 col-md-2">
                <div class="form-group">
                  <label>Incoming</label>
                  <br><a href="#" class="btn btn-lg btn-link">0</a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <label for="backorder">
                    <input id="backorder" type="checkbox" v-model="current.backorder">
                    <span class="faux-label">Allow customers to purchase this product when it's out of stock</span>
                  </label>
                </div>
              </div>
            </div>

            <h4>Shipping</h4>
            <hr>
            <div class="form-group">
              <label for="requiresShipping">
                <input id="requiresShipping" type="checkbox" v-model="current.requires_shipping">
                <span class="faux-label"> This product requires shipping</span>
              </label>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>Fulfillment Service</label>
                  <candy-select :options="['Option','Option','Option']"></candy-select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>
                    Weight
                    <em class="help-txt">Description on what weigth is used for.</em>
                  </label>
                  <div class="input-group input-group-full">
                    <input type="number" class="form-control" v-model="current.weight.value">
                    <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="current.weight.unit" :addon="true"></candy-select>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <p>Fields below will show dependant on fulfillment service.</p>
            <div class="row">
              <div class="col-xs-12 col-md-5">
                <div class="form-group">
                  <label>
                    Height
                    <em class="help-txt">Description on what height is used for.</em>
                  </label>
                  <div class="input-group input-group-full">
                    <input type="number" class="form-control" v-model="current.height.value">
                    <candy-select :options="['cm','mm', 'in']" v-model="current.height.unit" :addon="true"></candy-select>
                  </div>
                </div>
                <div class="form-group">
                  <label>
                    Width
                    <em class="help-txt">Description on what width is used for.</em>
                  </label>
                  <div class="input-group input-group-full">
                    <input type="number" class="form-control" v-model="current.width.value">
                    <candy-select :options="['cm','mm', 'in']" v-model="current.width.unit" :addon="true"></candy-select>
                  </div>
                </div>
                <div class="form-group">
                  <label>
                    Depth
                    <em class="help-txt">Description on what depth is used for.</em>
                  </label>
                  <div class="input-group input-group-full">
                    <input type="number" class="form-control" v-model="current.depth.value">
                    <candy-select :options="['cm','mm', 'in']" v-model="current.depth.unit" :addon="true"></candy-select>
                  </div>
                </div>
                <div class="form-group">
                  <label>
                    Volume
                    <em class="help-txt">Description on what volume is used for.</em>
                  </label>
                  <div class="input-group input-group-full">
                    <input type="number" class="form-control" :value="volume">
                    <candy-select :options="['l', 'ml']" v-model="current.volume.unit"></candy-select>
                  </div>
                </div>
                <button class="btn btn-danger" @click="deleteVariant(currentIndex)" v-if="variants.length > 1"><i class="fa fa-trash"></i> Delete variant</button>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-4 col-md-pull-8" v-if="variants.length > 1">
            <ul class="variant-list">
              <li v-for="(v, index) in variants">
                <a href="#" @click.prevent="selectVariant(index)" :class="{ 'active' : v.id == current.id }" title="">
                  <div class="variant-img" v-if="v.image">
                    <img src="img/placeholder/product.jpg" alt="Aquacomb">
                  </div>
                  <div class="variant-img" v-else>
                    <img src="/images/placeholder/no-image.svg" alt="Placeholder">
                  </div>
                  <div class="variant-options">
                    <template v-for="(option, label, index) in v.options">
                      <span class="option-label">{{ capitalize(label) }}</span> {{ option }},
                    </template>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
