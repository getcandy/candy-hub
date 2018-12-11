<script>
    export default {
        data() {
            return {
                request: apiRequest
            }
        },
        props: {
            product: {
                type: Object
            },
            languages: {
                type: Array
            },
            channels: {
                type: Array
            },
            group: {
                type: Object,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                this.request.send('put', '/products/' + this.product.id, { 'attributes' : this.product.attribute_data })
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('title-changed', {
                            title: this.product
                        });
                    }).catch(errors => {
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
            },
            getChannels(channels) {
                let arr = [];
                channels.forEach(channel => {
                    arr.push({
                        label: channel.name,
                        value: channel.handle
                    });
                });
                return arr;
            }
        },
        mounted() {
            Dispatcher.add('product-details', this);
        }
    }
</script>
<template>
    <div>
        <candy-attribute-translatable :languages="languages" :channels="getChannels(product.channels.data)"
                              :attributes="group.attributes.data" :attributeData="product.attribute_data"
                              :request="request">
        </candy-attribute-translatable>
    </div>
</template>