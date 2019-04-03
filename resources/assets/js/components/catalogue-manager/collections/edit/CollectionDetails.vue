<script>
    export default {
        data() {
            return {
                request: apiRequest
            }
        },
        props: {
            collection: {
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
                this.request.send('put', '/collections/' + this.collection.id, { 'attributes' : this.collection.attribute_data })
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                    }).catch(response => {
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
            Dispatcher.add('collection-details', this);
        }
    }
</script>
<template>
    <div>
        <candy-attribute-translatable :languages="languages" :channels="getChannels(collection.channels.data)"
                              :attributes="group.attributes.data" :attributeData="collection.attribute_data"
                              :request="request">
        </candy-attribute-translatable>
    </div>
</template>