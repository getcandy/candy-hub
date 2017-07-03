<template>
  <div class="row">
    <div class="col-xs-12 col-md-11">
      <h4>Customer Groups</h4>
      <hr>
      <table class="table">
        <thead>
          <tr>
            <th>Group</th>
            <th>Visible</th>
            <th>Purchaseable</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Guests</td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Retail</td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Trade</td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Partners</td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
            </td>
            <td>
              <select class="form-control selectpicker">
                <option>Yes</option>
                <option>No</option>
              </select>
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