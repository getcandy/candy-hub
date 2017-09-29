<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data() {
            return {
                loaded: false,
                category: {},
                attribute_groups: [],
                routes: [],
                languages: []
            }
        },
        props: {
            categoryId: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadLanguages();
            this.loadCategory(this.categoryId);
        },
        mounted() {
            CandyEvent.$on('category-updated', event => {
                this.loaded = false;
                this.loadCategory(this.categoryId);
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
                this.category = data;
                this.category.attributes = this.category.attribute_data;
                this.routes = this.category.routes.data;
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
                            value: lang.lang,
                            content: '<span class=\'flag-icon flag-icon-' + lang.iso + '\'></span> ' + lang.name
                        });
                    });
                });
            },
            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadCategory(id) {
                apiRequest.send('get', '/category/' + this.categoryId, {}, {
                    includes: 'attribute_groups'
                }).then(response => {
                    console.log(response);
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
                <candy-tabs>

                    <candy-tab name="Category Details" handle="category-details" :selected="true" dispatch="category-details">

                        <candy-tabs nested="true">
                            <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false">
                                <candy-category-details :category="category" :languages="languages"
                                                       :group="group">
                                </candy-category-details>
                            </candy-tab>
                        </candy-tabs>

                    </candy-tab>

                    <candy-tab name="Media">
                        <candy-media :product="product"></candy-media>
                    </candy-tab>

                    <!--
                                        <candy-tab name="Availability &amp; Pricing" handle="product-availability" dispatch="product-variants">
                                            <candy-product-availability :variants="variants" :product="product" :languages="languages"
                                                                        v-if="product"></candy-product-availability>
                                        </candy-tab>

                                        <candy-tab name="Associations">
                                            <candy-tabs nested="true">
                                                <candy-tab name="Categories" handle="categories" :selected="true" :badge="getCategoryCount()">
                                                    <candy-categories :product="product"></candy-categories>
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
                    -->
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
