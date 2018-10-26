<script>
    export default {
        data() {
            return {
                request: apiRequest,
                channels: [],
                languages: []
            }
        },
        props: {
            family: {
                type: Object
            },
            group: {
                type: Object|Array,
                default() {
                    return [];
                }
            }
        },
        methods: {
            save() {
                this.request.send('put', '/products/' + this.family.id, { 'attributes' : this.family.attributes })
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        CandyEvent.$emit('title-changed', {
                            title: this.family
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
            },
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
        },
        mounted() {
            Dispatcher.add('product-details', this);
            this.loadLanguages();
             this.request.send('get', '/channels/')
                .then(response => {
                    this.channels = response.data;
                }).catch(errors => {
                    CandyEvent.$emit('notification', {
                        level: 'error',
                        message: 'Missing / Invalid fields'
                    });
                });
        }
    }
</script>
<template>
    <div>
        <candy-attribute-translatable :languages="languages" :channels="getChannels(channels)"
                              :attributes="group.attributes.data" :attributeData="family.attribute_data"
                              :request="request" v-if="channels.length">
        </candy-attribute-translatable>
    </div>
</template>