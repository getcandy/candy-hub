<script>
    export default {
        data() {
            return {
              current: {}
            }
        },
        created() {
          this.current = this.variants[0];
        },
        methods: {
          selectVariant(index) {
            this.current = this.variants[index];
          },
          save() {
            alert('Save!');
          },
          capitalize(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
          }
        },
        computed: {
          volume() {
            return this.current.weight.value;
          }
        },
        props: {
          variants: {
            type: Array
          }
        }
    }
</script>

<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <h4>Variants</h4>
        </div>
      </div>
      <hr>
      <p>If creating new variant, nav to left will not have an active state and option fields will be empty.</p>
      <div class="row">
        <div class="col-xs-12 col-md-8 col-md-push-4">
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
                  <i class="fa fa-picture-o icon" aria-hidden="true"></i>
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
                <select class="form-control selectpicker">
                  <option>0%</option>
                  <option>5%</option>
                  <option>20%</option>
                </select>
              </div>
            </div>
          </div>

          <h4>Inventory</h4>
          <hr>
          <div class="row">
            <div class="col-xs-12 col-md-5">
              <div class="form-group">
                <label>Inventory Policy</label>
                <select class="form-control selectpicker">
                  <option disabled selected value>Select an Option</option>
                  <option>Option 1</option>
                  <option>Option 2</option>
                  <option>Option 3</option>
                </select>
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
                <select class="form-control selectpicker">
                  <option>Option</option>
                  <option>Option</option>
                  <option>Option</option>
                </select>
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
                  <candy-select :options="['lb', 'oz', 'kg', 'g']" v-model="current.weight.unit"></candy-select>
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
                  <candy-select :options="['cm','mm', 'in']" v-model="current.height.unit"></candy-select>
                </div>
              </div>
              <div class="form-group">
                <label>
                  Width
                  <em class="help-txt">Description on what width is used for.</em>
                </label>
                <div class="input-group input-group-full">
                  <input type="number" class="form-control" v-model="current.width.value">
                  <candy-select :options="['cm','mm', 'in']" v-model="current.width.unit"></candy-select>
                </div>
              </div>
              <div class="form-group">
                <label>
                  Depth
                  <em class="help-txt">Description on what depth is used for.</em>
                </label>
                <div class="input-group input-group-full">
                  <input type="number" class="form-control" v-model="current.depth.value">
                  <candy-select :options="['cm','mm', 'in']" v-model="current.depth.unit"></candy-select>
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
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-md-pull-8">
          <ul class="variant-list">
            <li v-for="(v, index) in variants">
              <a href="#" @click="selectVariant(index)" :class="{ 'active' : v.id == current.id }" title="">
                <div class="variant-img" v-if="v.image">
                  <img src="img/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <i aria-hidden="true" class="fa fa-picture-o icon" v-else></i>
                <div class="variant-options">
                  <template v-for="(option, label, index) in v.options">
                    {{ capitalize(label) }} {{ option }},
                  </template>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>