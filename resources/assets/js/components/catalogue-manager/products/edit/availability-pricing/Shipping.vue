<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <h4>Shipping</h4>
      <hr>
      <div class="row">
        <div class="col-xs-12 col-md-4">
          <div class="checkbox form-group">
            <input id="requiresShipping" type="checkbox" checked>
            <label for="requiresShipping">
              <span class="check"></span>
              <span class="faux-label">This product requires shipping</span>
            </label>
          </div>
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
        <div class="col-xs-12 col-md-4">
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
        <div class="col-xs-12 col-md-4">
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