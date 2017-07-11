<script>
    export default {
        data() {
            return {
              showModal: false,
              options: [
                {
                  name: ' ',
                  value: []
                }
              ],
              variants: []
            }
        },
        mounted() {
          Event.$on('tagged', value => {
            this.generateVariants();
          });
        },
        methods: {
          generateVariants() {
            let optionValues = [];
            this.variants = [];

            /**
             * We want to get the options into a format where we can
             * get all the variantions whilst keeping the option name
             * associated to it. The logic below will give us something like:
             * [[{size: 10}, {size: 20}, {size: 30}],[{colour: 'red'}]]
             *
             * So we can pass this to our allPossibleCases method and hopefully
             * it will generate the variants
             */
            this.options.forEach((option, index) => {
              let childValues = [];
              option.value.forEach((child, childIndex) => {
                childValues.push({[option.name.trim()] : child});
              });
              optionValues.push(childValues);
            });

            optionValues = this.allPossibleCases(optionValues);
            // console.log(this.variants);

            // {name: 'Colour', value: [10,20,30]}

            // {colour: 'red', size: 10}
            // [[{size: 10}, {size: 20}, {size: 30}],[{colour: 'red'}]]

            optionValues.forEach(variant => {
              var value = '';
              var data = [];

              console.log(variant);
              if (variant === Object(variant) && Object.prototype.toString.call(variant) !== '[object Array]') {
                let key = Object.keys(variant)
                value = key[0] + ' ' + variant[key[0]];
                data = variant;
              } else {
                value = variant;
              }


              this.variants.push({
                label: value,
                price: '',
                data: data,
                inventory: 1,
                sku: ''
              });
            });
          },
          allPossibleCases(arr) {
            if (arr.length == 1) {
              return arr[0];
            } else {
              var result = [];
              var allCasesOfRest = this.allPossibleCases(arr.slice(1));  // recur with the rest of array
              for (var i = 0; i < allCasesOfRest.length; i++) {
                for (var j = 0; j < arr[0].length; j++) {
                  let firstKeys = Object.keys(arr[0][j]);
                  let secondKeys = Object.keys(allCasesOfRest[i]);
                  result.push([
                    firstKeys[0] + ' ' + arr[0][j][firstKeys[0]] + ', ' + secondKeys[0] + ' ' + allCasesOfRest[i][secondKeys[0]]
                  ]);
                }
              }
              return result;
            }
          },
          addOption() {
            this.options.push({
              name: ' ',
              value: []
            });
          },
          removeOption(index) {
            if (this.options.length == 1) {
              alert('You must have at least one');
              return;
            }
            this.options.splice(index, 1);
            this.generateVariants();
          },
          removeVariant(index) {
            this.variants.splice(index, 1);
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
              <tr v-for="(option, index) in options">
                <td width="30%">
                  <input type="text" class="form-control" v-model="option.name">
                </td>
                <td width="60%">
                  <candy-taggable v-model="option.value"></candy-taggable>
                </td>
                <td align="right"><button class="btn btn-sm btn-default btn-action delete-row" @click="removeOption(index)"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td colspan="3"><button type="button" class="btn btn-default add-variant-option" @click="addOption">Add another option</button></td>
              </tr>
            </tbody>
          </table>
          <hr>
          <p>Modify generated variants ({{ variants.length }})</p>
          {{ variants }}
          <table class="table association-table">
            <thead>
              <tr>
                <th>Variant</th>
                <th>Price</th>
                <th>SKU</th>
                <th>Inventory</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="list">
                <tr v-for="(variant, index) in variants">
                  <td width="50">
                    {{ variant.label }}
                    {{ variant.data }}
                  </td>
                  <td width="40%">
                    <div class="input-group input-group-full">
                      <span class="input-group-addon">&pound;</span>
                      <input type="text" class="form-control" :value="variant.price">
                    </div>
                  </td>
                  <td width="40%">
                    <input type="text" class="form-control" :value="variant.sku" required>
                  </td>
                  <td width="10%"><input type="number" class="form-control" :value="variant.inventory"></td>
                  <td><button @click="removeVariant(index)">X</button></td>
                </tr>
            </tbody>
          </table>
        </div>
        <template slot="footer">
          <button class="btn btn-primary">Save Variants</button>
        </template>
      </candy-modal>
    </div>
</template>