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
                method: {}
            }
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        created() {
            this.loadMethod(this.id);
        },
        mounted() {
            CandyEvent.$on('method-updated', event => {
                this.loaded = false;
                this.loadMethod(this.id);
            });
            Dispatcher.add('save-shipping-method', this);
        },
        methods: {
            save() {
                apiRequest.send('PUT', '/shipping/' + this.method.id, this.method).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                })
            },
            /**
             * Loads the product by its encoded ID
             * @param  {String} id
             */
            loadMethod(id) {
                apiRequest.send('get', '/shipping/' + id, {}, {
                    includes: 'prices.customer_groups,prices.currency,zones,channels,attribute_groups.attributes'
                })
                .then(response => {
                    this.method = response.data;
                    this.loaded = true;
                    CandyEvent.$emit('title-changed', {
                        title: this.method
                    });

                    // apiRequest.send('GET', 'currencies/' + this.order.currency).then(response => {
                    //     this.currency = response.data;
                    //     this.loaded = true;
                    // });
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
                        <candy-shipping-details :method="method"></candy-shipping-details>
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
