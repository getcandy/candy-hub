<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data() {
            return {
                loaded: false,
                product: {},
                attribute_groups: [],
                purchasable: true,
                viewable: true,
                variants: [],
                routes: [],
                languages: []
            }
        },
        props: {
            productId: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadLanguages();
            this.loadProduct(this.productId);
        },
        mounted() {
            CandyEvent.$on('product-updated', event => {
                this.loaded = false;
                this.loadProduct(this.productId);
            });
            CandyEvent.$on('product_visibility', visible => {
                this.viewable = visible;
            });
            CandyEvent.$on('product_purchasable', purchasable => {
                this.purchasable = purchasable;
            });
        },
        methods: {
            /**
             * Decorates the data ready for the template to use
             * @param  {Object} data
             * @return
             */
            decorate(data) {
                this.attribute_groups = data.attribute_groups.data;
                this.product = data;
                this.product.attributes = this.product.attribute_data;
                this.variants = this.product.variants.data;
                this.routes = this.product.routes.data;
            },
            /**
             * Loads languages
             * @return
             */
            loadLanguages() {
                apiRequest.send('get', 'languages', [], []).then(response => {
                    response.data.forEach(lang => {
                        this.languages.push({
                            label: lang.name,
                            value: lang.iso,
                            content: '<span class=\'flag-icon flag-icon-' + lang.iso + '\'></span> ' + lang.name
                        });
                    });
                });
            },
            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadProduct(id) {
                apiRequest.send('get', '/products/' + this.productId, {}, {
                    includes: 'family,assets,assets.tags,attribute_groups,attribute_groups.attributes,layout,variants,routes,channels,customer_groups'
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
            <div class="alert alert-warning" v-if="!purchasable"><i class="fa fa-exclamation-triangle"
                                                                    aria-hidden="true"></i>
                This product cannot be purchased - <a href="#">click here to fix this</a></div>
            <div class="alert alert-danger" v-if="!viewable">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> This product cannot be viewed -
                <a href="#">click here to fix this</a>
            </div>

            <transition name="fade">
                <candy-tabs>

                    <candy-tab name="Product Details" handle="product-details" :selected="true">
                        <candy-product-details :product="product" :languages="languages"
                                               :groups="attribute_groups">

                        </candy-product-details>
                    </candy-tab>

                    <candy-tab name="Media">
                        <candy-media :product="product"></candy-media>
                    </candy-tab>

                    <candy-tab name="Availability &amp; Pricing" handle="product-availability">
                        <candy-product-availability :variants="variants" :product="product"
                                                    v-if="product"></candy-product-availability>
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
                                <candy-products :product="product"></candy-products>
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
                                <candy-locale-urls :languages="languages" :routes="routes"
                                                   :product="product"></candy-locale-urls>
                            </candy-tab>
                            <candy-tab name="Redirects" handle="redirects">
                                <candy-redirects :product="product" :routes="routes"></candy-redirects>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                </candy-tabs>
            </transition>
        </template>

        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>
    </div>

</template>

<style lang="scss" scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 1s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */
    {
        opacity: 0;
    }
</style>
