<template>
  <div>
    <!-- Add Locale URL Modal -->
    <div class="modal fade" id="addLocaleURL" tabindex="-1" role="dialog" aria-labelledby="addLocaleURL">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Locale URL</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <label>Locale</label>
                  <select class="form-control selectpicker">
                    <option data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                    <option data-content="<span class='flag-icon flag-icon-fr'></span> French">French</option>
                    <option data-content="<span class='flag-icon flag-icon-de'></span> German">German</option>
                  </select>
                </div>
              </div>
              <div class="col-xs-12 col-sm-9">
                <div class="form-group">
                  <label for="redirectURL">Enter the Locale URL you wish to add to this product.</label>
                  <input type="text" id="redirectURL" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Save Redirect</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Add Redirect Modal -->
    <div class="modal fade" id="addRedirect" tabindex="-1" role="dialog" aria-labelledby="addRedirect">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Redirect</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="redirectURL">Enter the URL you wish to redirect to this product.</label>
              <input type="text" id="redirectURL" class="form-control">
            </div>
            <div class="form-group">
              <label for="explaination">Brief description for the redirect</label>
              <textarea id="explaination" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Save Redirect</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete URL Modal -->
    <div class="modal fade" id="deleteURL" tabindex="-1" role="dialog" aria-labelledby="deleteURL">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Are you wish to delete this URL?</h4>
          </div>
          <div class="modal-body">
            <p>Once deleted this action can not be undone</p>
            <div class="form-group">
              <input type="text" class="form-control" value="/aquacomb/" disabled>
              <!-- Dynamically add corresponding URL -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Confirm Deletion</button>
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