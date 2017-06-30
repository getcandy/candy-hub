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

        <!-- Add Variant Modal -->
        <div class="modal fade" id="initialAddVariant" tabindex="-1" role="dialog" aria-labelledby="initialAddVariant">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Variant</h4>
              </div>
              <div class="modal-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Option name</th>
                      <th colspan="2">Option values</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="30%"><input type="text" class="form-control"></td>
                      <td width="60%"><input type="text" class="form-control" data-role="tagsinput"></td>
                      <td align="right"><button class="btn btn-sm btn-default btn-action delete-row"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                    </tr>
                    <tr>
                      <td colspan="3"><button type="button" class="btn btn-default add-variant-option">Add another option</button></td>
                    </tr>
                  </tbody>
                </table>
                <hr>
                <p>Modify generated variants</p>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Variant</th>
                      <th>Price</th>
                      <th>SKU</th>
                      <th>Inventory</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Option Name</td>
                      <td>
                        <div class="input-group input-group-full">
                          <span class="input-group-addon">&pound;</span>
                          <input type="text" class="form-control">
                        </div>
                      </td>
                      <td>
                        <input type="text" class="form-control">
                      </td>
                      <td><input type="number" class="form-control" value="1"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Continue</button>
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