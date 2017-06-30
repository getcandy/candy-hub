<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <h4>Collections</h4>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCollection">Add to a Collection</button>
        </div>
      </div>
      <hr>
      <table class="table">
        <thead>
          <tr>
            <td> </td>
            <td>Collection Name</td>
            <td colspan="2">URL</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td width="80"><img src="img/placeholder/product.jpg" alt="Aquacomb"></td>
            <td>Collection</td>
            <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeCollection"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
          </tr>
          <tr>
            <td width="80"><img src="img/placeholder/product.jpg" alt="Aquacomb"></td>
            <td>Collection</td>
            <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
            <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeCollection"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
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