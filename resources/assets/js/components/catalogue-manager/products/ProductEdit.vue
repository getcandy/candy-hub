<script>
    export default {
        data () {
            return {
              product: [],
              attribute_groups: [],
            }
        },
        props: {
          productId: {
            type: String,
            required: true
          }
        },
        created() {
          this.loadProduct(this.productId);
        },
        mounted() {
          Event.$on('save-product', () => this.saveProduct());
        },
        methods: {
          decorate (data) {
            this.attribute_groups = data.attribute_groups.data;
            this.product = data;
            this.product.attributes = this.product.attribute_data;
          },
          saveProduct() {
            Event.$emit('save-product-request', {
              url: '/api/v1/products/' + this.productId,
              method: 'put',
              data: this.product
            });
          },
          loadProduct (id) {
            axios.get('/api/v1/products/' + id, {
              params: {
                includes : 'family,attribute_groups,attribute_groups.attributes,layout'
              }
            })
            .then(response => this.decorate(response.data.data))
            .catch(error => console.log(error));
          }
        }
    }
</script>

<template>
  <div>
    <candy-tabs>
      <candy-tab name="Product Details" :selected="true">

        <candy-tabs nested="true">
          <template v-for="(group, index) in attribute_groups">
            <candy-tab :name="group.name" :selected="index == 0 ? true : false">
              <div class="form-group" v-for="input in group.attributes.data">
                <label>
                  {{ input.name }}
                </label>
                <input type="text" class="form-control" v-model="product['attribute_data'][input.handle]['en']">
              </div>
            </candy-tab>
          </template>
        </candy-tabs>

      </candy-tab>

      <candy-tab name="Availability &amp; Pricing">
        <h1>Availability &amp; Pricing</h1>
      </candy-tab>

      <candy-tab name="Collections">
        <h1>Collections</h1>
      </candy-tab>

      <candy-tab name="Associations">
        <h1>Associations</h1>
      </candy-tab>

    </candy-tabs>
  </div>
</template>