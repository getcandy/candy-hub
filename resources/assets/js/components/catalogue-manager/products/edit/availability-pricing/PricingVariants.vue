<template>
    <div>
      <div class="row">
        <div class="col-xs-12 col-md-11">
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <h4>Pricing</h4>
            </div>
            <div class="col-xs-12 col-sm-6 text-right">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#initialAddVariant">Add Variant</button>
            </div>
          </div>
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