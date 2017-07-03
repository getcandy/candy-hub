<template>
    <div>
        <div class="row">
          <div class="col-xs-12 col-md-11">
            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <h4>Locale URLs</h4>
              </div>
              <div class="col-xs-12 col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLocaleURL">Add Locale URL</button>
              </div>
            </div>
            <hr>
            <table class="table">
              <thead>
                <tr>
                  <td>URL</td>
                  <td colspan="3">Locale</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" class="form-control" value="/aquacomb/"></td>
                  <td><span class="flag-icon flag-icon-gb"></span></td>
                  <td><em>Default</em></td>
                  <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#deleteURL"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control" value="/peigne-aqua/"></td>
                  <td colspan="2"><span class="flag-icon flag-icon-fr"></span></td>
                  <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#deleteURL"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control" value="/aquakamm/"></td>
                  <td colspan="2"><span class="flag-icon flag-icon-de"></span></td>
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