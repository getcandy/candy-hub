<script>
    export default {
        data() {
            return {
                request: apiRequest,
                group: []
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
            groups: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                this.request.send('put', '/products/' + this.product.id, { 'attributes' : this.product.attributes })
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
            },
            fields(group) {
                /* TODO see if we can merge translatable
                let fields = {};

                $.each(group.attributes.data, function (key, attribute) {
                    fields[attribute.handle] = {
                        value: _.get(this.product.attributes, [attribute.handle]),
                        lookups: attribute.lookups,
                        type: attribute.type,
                        translatable: attribute.scopeable
                    };
                }.bind(this));
                return fields;
                */
            }
        },
        mounted() {
            Dispatcher.add('product-details', this);
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true">

            <template v-for="(group, index) in groups">
                <candy-tab :name="group.name" :selected="index == 0 ? true : false">
                    <candy-attribute-translatable :languages="languages" :channels="getChannels(product.channels.data)"
                                          :attributes="group.attributes.data" :attributeData="product.attributes"
                                          :request="request">
                    </candy-attribute-translatable>

                </candy-tab>
            </template>

        </candy-tabs>
    </div>
</template>