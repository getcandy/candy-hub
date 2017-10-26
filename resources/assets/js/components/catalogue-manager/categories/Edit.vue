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
                apiRequest.send('get', '/categories/' + this.categoryId, {}, {
                    includes: 'channels,assets,assets.tags,attribute_groups,attribute_groups.attributes,customer_groups,routes'
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
                <candy-tabs>

                    <candy-tab name="Category Details" handle="category-details" :selected="true" dispatch="category-details">
                        <candy-tabs nested="true">
                            <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="product-details">
                                <candy-category-details :category="category" :languages="languages" :group="group">
                                </candy-category-details>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                    <candy-tab name="Media">
                        <candy-media assetable="categories" :parent="category"></candy-media>
                    </candy-tab>

                    <candy-tab name="Availability &amp; Pricing" handle="category-availability">
                        <candy-category-availability :category="category" v-if="category"></candy-category-availability>
                    </candy-tab>

                    <candy-tab name="URLS">
                        <candy-tabs nested="true">
                            <candy-tab name="Locale URLS" handle="locale-urls" :selected="true">
                                <candy-urls :languages="languages" :routes="routes" :model="category" endpoint="categories"></candy-urls>
                            </candy-tab>
                            <candy-tab name="Redirects" handle="redirects">
                                <candy-redirects :model="category" endpoint="categories" :routes="routes"></candy-redirects>
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
