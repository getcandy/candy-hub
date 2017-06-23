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
        /**
         * Fires when the component has been mounted
         * @return void
         */
        mounted() {

          

          dispatcher.add('product-details', {
            component: this,
            method: 'saveProductDetails'
          });

          dispatcher.add('product-availability', {
            component: this,
            method: 'saveProductAvailability'
          });

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
           * Saves the product details
           * @return {Boolean}
           */
          saveProductDetails() {
            console.log('Saving a product details...');
            return true;
          },
          /**
           * Saves the product availability
           * @return {[type]} [description]
           */
          saveProductAvailability() {
            console.log('Saving product availability');
          },
          /**
           * 
           * axios({
              method: 'put',
              url: '/api/v1/products/' + this.productId,
              data: this.product
            }).then(response => console.log(response))
           * @param  {[type]} id [description]
           * @return {[type]}    [description]
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
                <candy-product-details></candy-product-details>
            </candy-tab>
          </template>
        </candy-tabs>

      </candy-tab>

      <candy-tab name="Availability &amp; Pricing" handle="product-availability">
        <h1>Availability &amp; Pricing</h1>
      </candy-tab>

      <candy-tab name="Collections">
        <candy-product-details></candy-product-details>
      </candy-tab>

      <candy-tab name="Associations">
        <candy-product-details></candy-product-details>
      </candy-tab>

    </candy-tabs>
  </div>
</template>