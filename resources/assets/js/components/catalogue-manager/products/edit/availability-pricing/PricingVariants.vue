<script>
    export default {
        data() {
            return {
              showModal: false,
              attributes: [],
              variants: []
            }
        },
        mounted() {
          this.attributes.push({
              name: ' ',
              value: ' '
          })
        },
        methods: {
          addAttribute() {
            this.attributes.push({
              name: ' ',
              value: ' '
            });
          },
          removeAttribute(index) {
            if (this.attributes.length == 1) {
              alert('You must have at least one');
              return;
            }
            this.attributes.splice(index, 1);
          }
        }
    }
</script>

<template>
    <div>
      <div class="row">
        <div class="col-xs-12 col-md-11">
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <h4>Pricing</h4>
            </div>
            <div class="col-xs-12 col-sm-6 text-right">
              <button type="button" class="btn btn-primary" @click="showModal = true">Add Variant</button>
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
      <candy-modal title="Create variant" v-show="showModal" @closed="showModal = false">
        <div slot="body">
          <table class="table">
            <thead>
              <tr>
                <th>Option name</th>
                <th colspan="2">Option values</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(attribute, index) in attributes">
                <td width="30%">
                  <input type="text" class="form-control" v-model="attribute.name">
                </td>
                <td width="60%"><input type="text" class="form-control" v-model="attribute.value"></td>
                <td align="right"><button class="btn btn-sm btn-default btn-action delete-row" @click="removeAttribute(index)"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td colspan="3"><button type="button" class="btn btn-default add-variant-option" @click="addAttribute">Add another option</button></td>
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
              <tr v-for="v in variants">
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
      </candy-modal>
    </div>
</template>