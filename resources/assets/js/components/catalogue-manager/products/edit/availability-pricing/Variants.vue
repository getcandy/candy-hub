<script>
    export default {
        data() {
            return {
              columns: []
            }
        },
        mounted() {
          this.variants.forEach(variant => {
            let options = variant.options;
            let keys = Object.keys(options);
            this.columns = this.columns.concat(keys).unique();
          });
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
          <h4>Variants &amp; Pricing</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <div class="btn-group">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#reorderOptions">Reorder Options</button>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editOptions">Edit Options</button>
            <a href="#editVariants" class="btn btn-primary" aria-controls="channels" role="tab" data-toggle="tab">Add Variant</a>
          </div>
        </div>
      </div>
      <hr>
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th v-for="col in columns">{{ col }}</th>
            <th>Price</th>
            <th>SKU</th>
            <th colspan="2">Inventory</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="v in variants">
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td v-for="option in v.options">
              <input type="text" class="form-control" :value="option">
            </td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control" :value="v.price">
              </div>
            </td>
            <td><input type="text" class="form-control" :value="v.sku"></td>
            <td><input type="number" class="form-control" :value="v.inventory"></td>
            <td width="120">
              <div class="btn-group">
                <a href="#editVariants" class="btn btn-default" aria-controls="channels" role="tab" data-toggle="tab">Edit</a>
                <button type="button" class="btn btn-default btn-action"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>