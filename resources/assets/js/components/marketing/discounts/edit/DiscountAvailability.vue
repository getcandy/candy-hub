<script>

    export default {
        data() {
            return {
                request: apiRequest,
                current: {},
                channels: [],
                discountChannels: []
            }
        },
        props: {
            discount: {
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
                    CandyEvent.$emit('discount_visibility', !((channels.length - visibleCount) == channels.length));
                },
                deep: true
            }
        },
        mounted() {
            Dispatcher.add('discount-availability', this);
        },
        methods: {
            save() {
                this.request.send('put', '/discounts/' + this.discount.id, this.discount).then(response => {
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
            <candy-tab name="Channels" handle="channels" dispatch="discount-availability" :selected="true">
                <candy-channel-association :channels="discount.channels.data"></candy-channel-association>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
