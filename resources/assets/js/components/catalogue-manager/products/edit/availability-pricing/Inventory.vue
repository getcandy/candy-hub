<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <h4>Inventory</h4>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-md-4">
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
        <div class="col-xs-6 col-md-4">
          <div class="form-group">
            <label>SKU</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="col-xs-12 col-md-3">
          <div class="form-group">
            <label>Quantity</label>
            <input type="number" class="form-control">
          </div>
        </div>
        <div class="col-xs-12 col-md-3">
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