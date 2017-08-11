<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <h4>Variant Title</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <a href="#variantsPricing" class="btn btn-primary" aria-controls="channels" role="tab" data-toggle="tab">Back to product</a>
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
              <div class="form-group">
                <label>Option Name</label>
                <input type="text" class="form-control" value="Red">
              </div>
              <div class="form-group">
                <label>Option Name</label>
                <input type="text" class="form-control" value="12kg">
              </div>
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
                  <input type="number" class="form-control">
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
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="col-xs-12 col-md-5">
              <div class="form-group">
                <label>Quantity</label>
                <input type="number" class="form-control">
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
              <div class="checkbox form-group">
                <input id="requiresShipping" type="checkbox">
                <label for="requiresShipping">
                  <span class="check"></span>
                  <span class="faux-label">Allow customers to purchase this product when it's out of stock</span>
                </label>
              </div>
            </div>
          </div>

          <h4>Shipping</h4>
          <hr>
          <div class="checkbox form-group">
            <input id="requiresShipping" type="checkbox" checked>
            <label for="requiresShipping">
              <span class="check"></span>
              <span class="faux-label">This product requires shipping</span>
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
                  <input type="number" class="form-control">
                  <select class="selectpicker input-group-addon">
                    <option>lb</option>
                    <option>oz</option>
                    <option>kg</option>
                    <option>g</option>
                  </select>
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
                  <input type="number" class="form-control">
                  <select class="selectpicker input-group-addon">
                    <option>cm</option>
                    <option>mm</option>
                    <option>in</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>
                  Width
                  <em class="help-txt">Description on what width is used for.</em>
                </label>
                <div class="input-group input-group-full">
                  <input type="number" class="form-control">
                  <select class="selectpicker input-group-addon">
                    <option>cm</option>
                    <option>mm</option>
                    <option>in</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>
                  Depth
                  <em class="help-txt">Description on what depth is used for.</em>
                </label>
                <div class="input-group input-group-full">
                  <input type="number" class="form-control">
                  <select class="selectpicker input-group-addon">
                    <option>cm</option>
                    <option>mm</option>
                    <option>in</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>
                  Volume
                  <em class="help-txt">Description on what volume is used for.</em>
                </label>
                <div class="input-group input-group-full">
                  <input type="number" class="form-control">
                  <select class="selectpicker input-group-addon">
                    <option>l</option>
                    <option>ml</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-md-pull-8">
          <h4>Variants</h4>
          <ul class="variant-list">
            <li>
              <a href="#" class="active" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
            <li>
              <a href="#" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
            <li>
              <a href="#" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
            <li>
              <a href="#" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
            <li>
              <a href="#" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
            <li>
              <a href="#" title="">
                <div class="variant-img">
                  <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                </div>
                <div class="variant-options">Option, Option</div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>