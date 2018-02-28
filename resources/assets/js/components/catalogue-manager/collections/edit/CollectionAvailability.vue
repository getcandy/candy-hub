<script>

    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                collectionChannels: [],
                customerGroups: []
            }
        },
        props: {
            collection: {
                type: Object
            },
            languages: {
                type: Array
            }
        },
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
                    CandyEvent.$emit('collection_visibility', !((channels.length - visibleCount) == channels.length));
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
                    CandyEvent.$emit('collection_purchasable', !((groups.length - purchasableCount) == groups.length));
                },
                deep: true
            }
        },
        mounted() {
            this.customerGroups = this.collection.customer_groups.data;
            Dispatcher.add('collection-availability', this);
        },
        methods: {
            save() {
                this.request.send('put', '/collections/' + this.collection.id, this.collection).then(response => {
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
        <candy-tabs nested="true">
            <candy-tab name="Channels" handle="channels" dispatch="collection-availability" :selected="true">
                <candy-channel-association :channels="collection.channels.data"></candy-channel-association>
            </candy-tab>
            <candy-tab name="Customer Groups" handle="customer-groups" dispatch="collection-availability">
                <candy-customer-groups :groups="customerGroups"></candy-customer-groups>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
