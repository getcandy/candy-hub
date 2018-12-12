<script>

    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                productChannels: [],
                customerGroups: []
            }
        },
        props: ['variants', 'product', 'languages'],
        watch: {
            channels: {
                handler(channels, oldVal) {
                    let visibleCount = 0;
                    channels.forEach(channel => {
                        if (channel.visible) {
                            visibleCount++;
                        }
                    });
                    // If there are no visible channels checked
                    CandyEvent.$emit('product_visibility', !((channels.length - visibleCount) == channels.length));
                },
                deep: true
            },
            customerGroups: {
                handler(groups, oldVal) {
                    let purchasableCount = 0;
                    groups.forEach(group => {
                        if (group.purchasable) {
                            purchasableCount++;
                        }
                    });
                    CandyEvent.$emit('product_purchasable', !((groups.length - purchasableCount) == groups.length));
                },
                deep: true
            }
        },
        mounted() {
            this.customerGroups = this.product.customer_groups.data;
            Dispatcher.add('product-availability', this);
        },
        methods: {
            save() {
                this.request.send('put', '/products/' + this.product.id, this.product).then(response => {
                    CandyEvent.$emit('notification', {
                        level: 'success'
                    });
                }).catch(response => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
            }
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true" v-if="product" parent="availabilitypricing">
            <candy-tab name="Pricing & Variants" handle="pricing-variants" :selected="true" dispatch="product-variants">
                <candy-variants :product="product" :languages="languages"></candy-variants>
            </candy-tab>
            <candy-tab name="Optional Extras" handle="extras">
                Coming soon
            </candy-tab>
            <candy-tab name="Channels" handle="channels" dispatch="product-availability">
                <candy-channel-association :channels="product.channels.data"></candy-channel-association>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups" dispatch="product-availability">
                <candy-customer-groups :groups="customerGroups"></candy-customer-groups>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
