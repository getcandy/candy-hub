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
            groups: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                console.log(2);
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
            }
        },
        mounted() {
            CandyEvent.$emit('current-tab', this);
        }
    }
</script>
<template>
    <div>
        <candy-tabs nested="true">

            <template v-for="(group, index) in groups">
                <candy-tab :name="group.name" :selected="index == 0 ? true : false">

                    <candy-attribute-data :languages="languages" :channels="getChannels(product.channels.data)"
                                          :attributes="group.attributes.data" :attributeData="product.attributes"
                                          :request="request">
                    </candy-attribute-data>

                </candy-tab>
            </template>

        </candy-tabs>
    </div>
</template>