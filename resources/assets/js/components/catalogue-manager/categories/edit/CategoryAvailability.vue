<script>

    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                customerGroups: []
            }
        },
        props: ['variants', 'category', 'languages'],
        watch: {
            channels: {
                handler(channels, oldVal) {
                    let visibleCount = 0;
                    channels.forEach(channel => {
                        if (channel.visible) {
                            visibleCount++;
                        }
                    });
                },
                deep: true
            }
            // customerGroups: {
            //     handler(groups, oldVal) {
            //         let purchasableCount = 0;
            //         groups.forEach(group => {
            //             if (group.purchasable) {
            //                 purchasableCount++;
            //             }
            //         });
            //     },
            //     deep: true
            // }
        },
        mounted() {
            // this.groups = this.category.customer_groups.data;
            // Dispatcher.add('product-availability', this);
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
        <candy-tabs nested="true" v-if="category">
            <candy-tab name="Channels" handle="channels" dispatch="category-availability" :selected="true">
                <candy-channel-association :channels="category.channels.data"></candy-channel-association>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups" dispatch="product-availability">
                <candy-customer-groups :groups="customerGroups"></candy-customer-groups>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
