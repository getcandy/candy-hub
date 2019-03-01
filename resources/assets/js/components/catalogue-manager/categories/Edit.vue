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
            url(id) {
                return route('hub.categories.edit', id);
            },
            /**
             * Decorates the data ready for the template to use
             * @param  {Object} data
             * @return
             */
            decorate(data) {

                let groups = [];
                _.each(data.attributes.data, attribute => {
                    let exists = _.find(groups, group => {
                        return group.handle == attribute.group.data.handle;
                    });
                    if (attribute.group && !exists) {
                        groups.push(attribute.group.data);
                    }
                });

                this.attribute_groups = groups;

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
                    full_response: true,
                    includes: 'channels,layout,assets.transforms,assets.tags,children,parent,attributes.group.attributes,customerGroups,routes,products,children.products'
                }).then(response => {
                    this.decorate(response.data);
                    document.title = this.$options.filters.attribute(this.category, 'name') + ' Category - GetCandy';
                    this.loaded = true;
                    CandyEvent.$emit('title-changed', {
                        title: this.category
                    });
                }).catch(error => {
                });
            }
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">

                <div class="panel" v-if="category.parent">
                    <div class="panel-body">
                        <i class="fa fa-info-circle text-info"></i> This category is a descendant of <a :href="url(category.parent.data.id)"><strong>{{ category.parent.data|attribute('name') }}</strong></a>
                    </div>
                </div>


                <candy-tabs initial="categorydetails">

                    <candy-tab name="Category Details" handle="category-details" :selected="true" dispatch="category-details">
                        <candy-tabs nested="true">
                            <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="product-details">
                                <candy-category-details :category="category" :languages="languages" :group="group">
                                </candy-category-details>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                    <candy-tab name="Media" dispatch="save-media">
                        <candy-media assetable="categories" :parent="category"></candy-media>
                    </candy-tab>

                    <candy-tab name="Availability &amp; Pricing" handle="category-availability" dispatch="category-availability">
                        <candy-category-availability :category="category" v-if="category"></candy-category-availability>
                    </candy-tab>

                    <candy-tab name="Associations" dispatch="product-positioning">
                        <candy-tabs nested="true">
                            <candy-tab name="Products" :selected="true" dispatch="product-positioning">
                                <candy-category-product-positioning :category-id="category.id" :sort="category.sort" :products="category.products.data"></candy-category-product-positioning>
                            </candy-tab>
                            <candy-tab name="Children Categories">
                                <candy-category-nodes :nodes="category.children.data" :category-id="category.id"></candy-category-nodes>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>

                    <candy-tab name="Display" dispatch="category-display" v-if="category">
                        <candy-category-display :current="category.layout" :category-id="category.id"></candy-category-display>
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
