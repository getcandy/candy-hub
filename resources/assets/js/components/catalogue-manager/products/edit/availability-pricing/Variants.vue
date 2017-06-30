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
            <th>Size</th>
            <th>Color</th>
            <th>Price</th>
            <th>SKU</th>
            <th colspan="2">Inventory</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td><input type="text" class="form-control" value="12kg"></td>
            <td><input type="text" class="form-control" value="Red"></td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control">
              </div>
            </td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control" value="1"></td>
            <td width="120">
              <div class="btn-group">
                <a href="#editVariants" class="btn btn-default" aria-controls="channels" role="tab" data-toggle="tab">Edit</a>
                <button type="button" class="btn btn-default btn-action"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td><input type="text" class="form-control" value="12kg"></td>
            <td><input type="text" class="form-control" value="Red"></td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control">
              </div>
            </td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control" value="1"></td>
            <td width="120">
              <div class="btn-group">
                <a href="#editVariants" class="btn btn-default" aria-controls="channels" role="tab" data-toggle="tab">Edit</a>
                <button type="button" class="btn btn-default btn-action"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td><input type="text" class="form-control" value="12kg"></td>
            <td><input type="text" class="form-control" value="Red"></td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control">
              </div>
            </td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control" value="1"></td>
            <td width="120">
              <div class="btn-group">
                <a href="#editVariants" class="btn btn-default" aria-controls="channels" role="tab" data-toggle="tab">Edit</a>
                <button type="button" class="btn btn-default btn-action"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td><input type="text" class="form-control" value="12kg"></td>
            <td><input type="text" class="form-control" value="Red"></td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control">
              </div>
            </td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control" value="1"></td>
            <td width="120">
              <div class="btn-group">
                <a href="#editVariants" class="btn btn-default" aria-controls="channels" role="tab" data-toggle="tab">Edit</a>
                <button type="button" class="btn btn-default btn-action"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <a href="" data-toggle="modal" data-target="#variantImage">
                <img src="img/placeholder/product.jpg" alt="Aquacomb">
              </a>
            </td>
            <td><input type="text" class="form-control" value="12kg"></td>
            <td><input type="text" class="form-control" value="Red"></td>
            <td>
              <div class="input-group input-group-full">
                <span class="input-group-addon">&pound;</span>
                <input type="number" class="form-control">
              </div>
            </td>
            <td><input type="text" class="form-control"></td>
            <td><input type="number" class="form-control" value="1"></td>
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

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>