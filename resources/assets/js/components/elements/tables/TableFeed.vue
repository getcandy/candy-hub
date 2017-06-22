<script>
  export default {
    props: [
      'headings',
      'items'
    ],
    data () {
        return {
            rows: []
        }
    },
    mounted () {
      this.rows = this.items;
      console.log(this.items);
    }
  }
</script>

<template>
  <div>
    <table class="table table-striped product-table">
      <thead>
        <tr>
          <th>
            <div class="checkbox bulk-options">
              <input id="prodSelect1" type="checkbox" class="select-all">
              <label for="prodSelect1"><span class="check"></span></label>
              <i class="fa fa-caret-down" aria-hidden="true"></i>

              <div class="bulk-actions">
                <div class="border-inner">
                  1 product selected
                  <a href="#" class="btn btn-outline btn-sm">Edit</a>
                  <a href="#" class="btn btn-outline btn-sm">Publish</a>
                  <a href="#" class="btn btn-outline btn-sm">Hide</a>
                  <a href="#" class="btn btn-outline btn-sm">Delete</a>
                </div>
                <div class="all-selected">
                  <em>All products one this page are selected</em>
                </div>
              </div>
            </div>
          </th>
          <th v-for="h in headings">{{ h }}</th>
        </tr>
      </thead>
      <tbody>
        <tr></tr>
      </tbody>
    </table>
  </div>
</template>