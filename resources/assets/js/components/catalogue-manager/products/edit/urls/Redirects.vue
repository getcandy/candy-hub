<template>
   <div>
       <div class="row">
          <div class="col-xs-12 col-md-11">
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <h4>Redirect URLs</h4>
              </div>
              <div class="col-xs-12 col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRedirect">Add Redirect</button>
              </div>
            </div>
            <hr>
            <table class="table">
              <thead>
                <tr>
                  <td>Redirect URL</td>
                  <td>Info</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" class="form-control" value="/new-products/aquacomb"></td>
                  <td><button class="btn btn-sm btn-default btn-action" data-toggle="popover" data-placement="top" data-content="Here is a brief explanation about the redirect."><i class="fa fa-question-circle-o" aria-hidden="true"></i></button></td>
                  <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#deleteURL"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                </tr>
              </tbody>
            </table>
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