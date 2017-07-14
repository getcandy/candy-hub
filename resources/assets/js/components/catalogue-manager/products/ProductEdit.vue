<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data () {
            return {
              loaded: false,
              product: {},
              attribute_groups: [],
              variants: [],
              routes: []
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
          Event.$on('product-updated', event => {
            this.loadProduct();
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
            this.variants = this.product.variants.data;
            this.routes = this.product.routes.data;
          },
          /**
           * Loads the product by its encoded ID
           * @param  {String} id
           */
          loadProduct (id) {
            apiRequest.send('get', '/products/' + this.productId, {}, {
              includes : 'family,attribute_groups,attribute_groups.attributes,layout,variants,routes,channels'
            }).then(response => {
                this.decorate(response.data);
                this.loaded = true;
              }).catch(error => {
            });
          }
        }
    }
</script>

<template>
  <div>
      <template v-if="loaded">
        <transition name="fade">
          <candy-tabs >
            <candy-tab name="Product Details" handle="product-details" :selected="true">
              <candy-product-details :product="product" :groups="attribute_groups"></candy-product-details>
            </candy-tab>
            <candy-tab name="Media">
              <candy-media></candy-media>
            </candy-tab>

            <candy-tab name="Availability &amp; Pricing" handle="product-availability">
              <candy-product-availability :variants="variants" :product="product" v-if="product"></candy-product-availability>
            </candy-tab>

            <candy-tab name="Associations">
              <candy-tabs nested="true">
                <candy-tab name="Categories" handle="categories" :selected="true">
                - Tab will require counter to show how many category associations this product has
                  <candy-categories></candy-categories>
                </candy-tab>
                <candy-tab name="Collections" handle="collections">
                  Tab will require counter to show how many collection associations this product has
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
                  <candy-locale-urls :routes="routes"></candy-locale-urls>
                </candy-tab>
                <candy-tab name="Redirects" handle="redirects">
                  <candy-redirects></candy-redirects>
                </candy-tab>
              </candy-tabs>
              <candy-url-modals></candy-url-modals>
            </candy-tab>
          </candy-tabs>
        </transition>
      </template>

      <div v-else>
        <div class="page-loading">
          <span><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></span> <strong>Loading</strong>
        </div>
      </div>
  </div>

</template>

<style lang="scss" scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
  }
  .page-loading {
    text-align:center;
    margin-top:25%;
    color:#bdbdbd;
    span {
      display:block;
      margin-bottom:1em;
    }
    strong {
      font-size:.875em;
      text-transform:uppercase;
      letter-spacing:1px;
    }
    i {
      font-size:3em;
    }
  }
</style>