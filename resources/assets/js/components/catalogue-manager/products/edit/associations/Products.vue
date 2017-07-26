<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <h4>Product Associations</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct">Add a Product</button>
        </div>
      </div>
      <hr>

      <div class="custom-radio-group">
        <span class="group-label">Toggle Product Association:</span>
        <div class="toggle-radio">
          <input type="radio" name="products" id="showAll" value="showAll" checked="checked">
          <label for="showAll">
            <span class="check"></span>
            <span class="faux-label">Show All</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="upsells" value="upsells">
          <label for="upsells">
            <span class="check"></span>
            <span class="faux-label">Upsells</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="crossSells" value="crossSells">
          <label for="crossSells">
            <span class="check"></span>
            <span class="faux-label">Cross-sells</span>
          </label>
        </div>
        <div class="toggle-radio">
          <input type="radio" name="products" id="alternate" value="alternate">
          <label for="alternate">
            <span class="check"></span>
            <span class="faux-label">Alternate</span>
          </label>
        </div>
      </div>

      <table id="productAssociations" class="table">
        <thead>
          <tr>
            <th width="80"></th>
            <th>Name</th>
            <th>URL</th>
            <th colspan="2">Type</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td width="80"><img src="/images/placeholder/product.jpg" alt="Aquacomb"></td>
            <td>AquaComb</td>
            <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
            <td><span class="label label-primary">Upsell</span></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
          </tr>
        </tbody>
      </table>
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