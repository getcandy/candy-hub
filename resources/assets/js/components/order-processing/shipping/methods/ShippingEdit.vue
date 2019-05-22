<!--
  Product Edit
  This component is responsible for displaying the product edit page.
 -->
<script>
    import ShippingUsers from './edit/ShippingUsers.vue';

    export default {
        data() {
            return {
                title: '',
                loaded: false,
                method: {},
                attribute_groups: [],
                languages: []
            }
        },
        components: {
            'candy-shipping-users': ShippingUsers
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadLanguages();
            this.loadMethod(this.id);
        },
        mounted() {
            CandyEvent.$on('method-updated', event => {
                this.loaded = false;
                this.loadMethod(this.id);
            });
            CandyEvent.$on('shipping-prices-updated', event => {
                this.loaded = false;
                this.loadMethod(this.id);
            });
            Dispatcher.add('save-shipping-method', this);
        },
        methods: {
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
            save() {
                apiRequest.send('PUT', '/shipping/' + this.method.id, this.method).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                })
            },
            decorate(data) {

                this.attribute_groups = data.attribute_groups.data;

                // Get all groups associated to this product.
                let groups = [];

                _.each(data.attributes.data, attribute => {
                    if (!data.attribute_data[attribute.handle]) {
                        this.$set(data.attribute_data, attribute.handle, {
                            webstore: {
                                en: ""
                            }
                        });
                    }

                    let exists = _.find(groups, group => {
                        return group.handle == attribute.group.data.handle;
                    });
                    if (attribute.group && !exists) {
                        let group = attribute.group.data;
                        let attributes = group.attributes.data;
                        _.each(attributes, (att, index) => {
                            if (!data.attribute_data[att.handle]) {
                                delete attributes[index];
                            }
                        });
                        group.attributes.data = _.filter(attributes);
                        groups.push(group);
                    }
                });

                groups = _.orderBy(groups, 'position', 'asc');

                this.attribute_groups = groups;
                this.method = data;
            },

            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadMethod(id) {
                apiRequest.send('get', '/shipping/' + id, {}, {
                    includes: 'attributes.group.attributes,prices.customer_groups,prices.currency,prices.zone,zones,channels,attribute_groups.attributes,users'
                })
                .then(response => {
                    this.decorate(response.data);
                    this.loaded = true;
                    CandyEvent.$emit('title-changed', {
                        title: this.method
                    });
                    document.title = this.$options.filters.attribute(this.method, 'name') +
                        ' Shipping Method - GetCandy';
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
                <candy-tabs initial="save-shipping-method">
                    <candy-tab name="Basic information"  dispatch="save-shipping-method" :selected="true">
                        <candy-tabs nested="true">
                            <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="shipping-details">
                                <candy-shipping-details :method="method" :languages="languages" :group="group"></candy-shipping-details>
                            </candy-tab>
                        </candy-tabs>
                    </candy-tab>
                    <candy-tab name="Availability & Pricing">
                        <candy-tabs nested="true">
                            <candy-tab name="Prices" selected="true">
                                <candy-shipping-prices :method="method"></candy-shipping-prices>
                            </candy-tab>
                            <candy-tab name="Zones" dispatch="save-shipping-method-zones">
                                <candy-shipping-method-zones :method="method"></candy-shipping-method-zones>
                            </candy-tab>
                            <candy-tab name="Channels" dispatch="save-shipping-method">
                                <candy-channel-association :channels="method.channels.data"></candy-channel-association>
                            </candy-tab>
                            <candy-tab name="Users" dispatch="save-shipping-method">
                                <candy-shipping-users :method="method"></candy-shipping-users>
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
