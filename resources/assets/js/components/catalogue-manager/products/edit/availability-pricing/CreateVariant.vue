<script>
    export default {
        data() {
            return {
              request: {},
              modalOpen: false,
              options: [
                {
                  name: ' ',
                  value: []
                }
              ],
              variants: []
            }
        },
        props: {
          product: {
            type: Object
          },
          showModal: {
            type: Boolean,
            default: false
          }
        },
        created() {
          this.modalOpen = this.showModal;
        },
        methods: {
          save() {
              apiRequest.send('post', '/products/' + this.product.id + '/variants', {'variants' : this.variants})
              .then(response => {
                  Event.$emit('notification', {
                      level: 'success'
                  });
                  Event.$emit('product-updated');
                  this.variants = [];
                  this.options = [];
                  this.showModal = false;
              }).catch(response => {
                  Event.$emit('notification', {
                      level: 'error',
                      message: 'Missing / Invalid fields'
                  });
              });
          },
          /**
           * Generates the variants
           * @return {Array}
           */
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

            // Get all possible values
            optionValues = this.getAllCombinations(optionValues);

            optionValues.forEach(variant => {
              let label = '';

              let data = {};

              variant.forEach((value, index) => {
                let keys = Object.keys(value);
                label += keys[0] + ' ' + value[keys[0]] + ((index + 1) < variant.length ? ', ' : ' ');
                data[keys[0].toLowerCase()] = value[keys[0]];
              });

              this.variants.push({
                label: label,
                price: '',
                data: data,
                inventory: 1,
                sku: ''
              });
            });
          },
          /**
           * Gets all the possible combinations for the variants
           * @param  {Array} arraysToCombine
           * @return {Array}
           */
          getAllCombinations(arraysToCombine) {
              var divisors = [];
              for (var i = arraysToCombine.length - 1; i >= 0; i--) {
                 divisors[i] = divisors[i + 1] ? divisors[i + 1] * arraysToCombine[i + 1].length : 1;
              }
              function getPermutation(n, arraysToCombine) {
                 var result = [],
                     curArray;
                 for (var i = 0; i < arraysToCombine.length; i++) {
                    curArray = arraysToCombine[i];
                    result.push(curArray[Math.floor(n / divisors[i]) % curArray.length]);
                 }
                 return result;
              }
              var numPerms = arraysToCombine[0].length;
              for(var i = 1; i < arraysToCombine.length; i++) {
                  numPerms *= arraysToCombine[i].length;
              }
              var combinations = [];
              for(var i = 0; i < numPerms; i++) {
                  combinations.push(getPermutation(i, arraysToCombine));
              }
              return combinations;
          },
          /**
           * Adds an option
           */
          addOption() {
            this.options.push({
              name: ' ',
              value: []
            });
          },
          /**
           * Removes an option
           * @param  {Int} index
           */
          removeOption(index) {
            if (this.options.length == 1) {
              alert('You must have at least one');
              return;
            }
            this.options.splice(index, 1);
            this.generateVariants();
          },
          /**
           * Removes a variant from the list
           * @param  {int} index
           */
          removeVariant(index) {
            this.variants.splice(index, 1);
          }
        }
    }
</script>

<template>
  <div>
    <button class="btn btn-primary" @click="modalOpen = true">Add new variant</button>
    <candy-modal title="Create variant" v-show="modalOpen" @closed="modalOpen = false">
      <div slot="body">
        <table class="table association-table variants-table">
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
          </tbody>
          <tfooter>
            <tr>
              <td colspan="3"><button type="button" class="btn btn-default add-variant-option" @click="addOption">Add another option</button><button class="btn btn-success" @click="generateVariants()">Generate variants</button></td>
            </tr>
          </tfooter>
        </table>

        <hr>
        <p>Modify generated variants ({{ variants.length }})</p>
        <table class="table association-table variants-table">
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
                </td>
                <td width="40%">
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">&pound;</span>
                    <input type="text" class="form-control" v-model="variant.price">
                  </div>
                </td>
                <td width="40%">
                  <input type="text" class="form-control" v-model="variant.sku" required>
                </td>
                <td width="10%"><input type="number" class="form-control" v-model="variant.inventory"></td>
                <td><button class="btn btn-sm btn-default" @click="removeVariant(index)"><i class="fa fa-times" aria-hidden="true"></i></button></td>
              </tr>
          </tbody>
        </table>
      </div>
      <template slot="footer">
        <button class="btn btn-primary" @click="save()">Save Variants</button>
      </template>
    </candy-modal>
  </div>
</template>