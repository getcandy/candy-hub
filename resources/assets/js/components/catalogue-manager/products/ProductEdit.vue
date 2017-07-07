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

              console.log(data);
            this.attribute_groups = data.attribute_groups.data;
            this.product = data;
            this.product.attributes = this.product.attribute_data;
          },
          /**
           * Loads the product by its encoded ID
           * @param  {String} id
           */
          loadProduct (id) {
            apiRequest.send('get', '/products/' + this.productId, {}, {
              includes : 'family,attribute_groups,attribute_groups.attributes,layout'
            }).then(response => this.decorate(response.data.data));
          }
        }
    }
</script>

<template>
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

    <candy-tab name="Media">
      <candy-media></candy-media>
    </candy-tab>

    <candy-tab name="Availability &amp; Pricing" handle="product-availability">
      <candy-tabs nested="true">
        <candy-tab name="Pricing & Variants" handle="pricing-variants" :selected="true">
          <candy-pricing-variants></candy-pricing-variants>
        </candy-tab>
        <candy-tab name="Inventory" handle="inventory">
          <candy-inventory></candy-inventory>
        </candy-tab>
        <candy-tab name="Shipping" handle="shipping">
          <candy-shipping></candy-shipping>
        </candy-tab>
        <candy-tab name="Channels" handle="channels">
          <candy-channels></candy-channels>
        </candy-tab>
        <candy-tab name="Customer Groups" handle="customer-groups">
          <candy-customer-groups></candy-customer-groups>
        </candy-tab>
        <candy-tab name="Discounts" handle="discounts">
          <candy-discounts></candy-discounts>
        </candy-tab>
      </candy-tabs>
    </candy-tab>

    <candy-tab name="if Variant" handle="if-variant">
      <candy-tabs nested="true">
        <candy-tab name="Pricing & Variants" handle="pricing-variants_variants-added" :selected="true">
          <candy-variants></candy-variants>
        </candy-tab>
        <candy-tab name="Edit Variant" handle="edit-variant_variants-added">
          <!-- This tab needs to be hidden from the sub navigation -->
          <candy-edit-variant></candy-edit-variant>
        </candy-tab>
        <candy-tab name="Channels" handle="channels_variants-added">
          <candy-channels></candy-channels>
        </candy-tab>
        <candy-tab name="Customer Groups" handle="customer-groups_variants-added">
          <candy-customer-groups></candy-customer-groups>
        </candy-tab>
        <candy-tab name="Discounts" handle="discounts_variants-added">
          <candy-discounts></candy-discounts>
        </candy-tab>
      </candy-tabs>
      <candy-avalability-pricing-modals></candy-avalability-pricing-modals>
    </candy-tab>

    <candy-tab name="Associations">
      <candy-tabs nested="true">
        <candy-tab name="Categories" handle="categories" :selected="true">
          <!-- Tab will require counter to show how many category associations this product has -->
          <candy-categories></candy-categories>
        </candy-tab>
        <candy-tab name="Collections" handle="collections">
          <!-- Tab will require counter to show how many collection associations this product has -->
          <candy-collections></candy-collections>
        </candy-tab>
        <candy-tab name="Products" handle="products">
          <candy-products></candy-products>
        </candy-tab>
      </candy-tabs>
      <candy-association-modals></candy-association-modals>
    </candy-tab>

    <candy-tab name="Display">
      <candy-display></candy-display>
    </candy-tab>

    <candy-tab name="URLS">
      <candy-tabs nested="true">
        <candy-tab name="Locale URLS" handle="locale-urls" :selected="true">
          <candy-locale-urls></candy-locale-urls>
        </candy-tab>
        <candy-tab name="Redirects" handle="redirects">
          <candy-redirects></candy-redirects>
        </candy-tab>
      </candy-tabs>
      <candy-url-modals></candy-url-modals>
    </candy-tab>

  </candy-tabs>
</template>