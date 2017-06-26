<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
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
        methods: {
          /**
           * Decorates the data ready for the template to use
           * @param  {Object} data
           * @return
           */
          decorate (data) {
            this.attribute_groups = data.attribute_groups.data;
            this.product = data;
            this.product.attributes = this.product.attribute_data;
          },
          /**
           * Loads the product by its encoded ID
           * @param  {String} id
           */
          loadProduct (id) {
            axios.get('/api/v1/products/' + id, {
              params: {
                includes : 'family,attribute_groups,attribute_groups.attributes,layout'
              }
            }).then(response => this.decorate(response.data.data))
              .catch(error => console.log(error));
          }
        }
    }
</script>

<template>
  <div>
    <candy-tabs>
      <candy-tab name="Product Details" handle="product-details" :selected="true">
        <candy-tabs nested="true">
          <template v-for="(group, index) in attribute_groups">
            <candy-tab :name="group.name" :selected="index == 0 ? true : false">
                <candy-product-details :group="group" :product="product"></candy-product-details>
            </candy-tab>
          </template>
        </candy-tabs>
      </candy-tab>

      <candy-tab name="Availability &amp; Pricing" handle="product-availability">
        <h1>Availability &amp; Pricing</h1>
      </candy-tab>

      <candy-tab name="Collections">
      </candy-tab>

      <candy-tab name="Associations">
      </candy-tab>

    </candy-tabs>
  </div>
</template>