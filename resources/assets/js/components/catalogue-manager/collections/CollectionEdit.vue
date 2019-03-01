<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    export default {
        data() {
            return {
                title: '',
                loaded: false,
                collection: {},
                attribute_groups: [],
                routes: [],
                languages: []
            }
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadLanguages();
            this.loadCollection(this.id);
        },
        mounted() {
            CandyEvent.$on('collection-updated', event => {
                this.loaded = false;
                this.loadCategory(this.id);
            });
        },
        methods: {
            /**
             * Decorates the data ready for the template to use
             * @param  {Object} data
             * @return
             */
            decorate(data) {
                this.collection = data;
                this.attribute_groups = data.attribute_groups.data;
                this.collection.attributes = this.collection.attribute_data;
                this.routes = this.collection.routes.data;
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
            loadCollection(id) {
                apiRequest.send('get', '/collections/' + id, {}, {
                    includes: 'channels,assets,assets.tags,attribute_groups,attribute_groups.attributes,customer_groups,routes,products'
                })
                .then(response => {
                    this.decorate(response.data);
                    this.loaded = true;

                    CandyEvent.$emit('title-changed', {
                        prefix: 'Editing',
                        title: this.collection
                    });

                    document.title = this.$options.filters.attribute(this.collection, 'name') + ' Collection - GetCandy';
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
                <candy-tabs initial="collectiondetails">
                    <candy-tab name="Collection Details" handle="collection-details" :selected="true" dispatch="collection-details">
                        <candy-tabs nested="true">
                            <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="collection-details">
                                <candy-collection-details :collection="collection" :languages="languages" :group="group">   </candy-collection-details>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                    <candy-tab name="Media" dispatch="save-media">
                        <candy-media assetable="collections" :parent="collection"></candy-media>
                    </candy-tab>

                    <candy-tab name="Availability" handle="collection-availability" dispatch="collection-availability">
                        <candy-collection-availability :collection="collection" :languages="languages"></candy-collection-availability>
                    </candy-tab>

                    <candy-tab name="Associations">
                        <candy-tabs nested="true">
                            <candy-tab name="Products" :selected="true">
                                <candy-collection-products :collection-id="collection.id" :products="collection.products.data"></candy-collection-products>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                    <candy-tab name="URLS">
                        <candy-tabs nested="true">
                            <candy-tab name="Locale URLS" handle="locale-urls" :selected="true">
                                <candy-urls :languages="languages" :routes="routes" :model="collection" endpoint="collections"></candy-urls>
                            </candy-tab>
                            <candy-tab name="Redirects" handle="redirects">
                                <candy-redirects :model="collection" endpoint="collections" :routes="routes"></candy-redirects>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                </candy-tabs>
            </transition>
        </template>

        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
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
