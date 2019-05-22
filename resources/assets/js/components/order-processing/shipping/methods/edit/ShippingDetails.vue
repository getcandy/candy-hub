<script>
    export default {
        data() {
            return {
                request: apiRequest
            }
        },
        props: {
            method: {
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
        }
    }
</script>

<style lang="scss">
    .group {
        border:2px solid #ebebeb;
        padding:2em;
    }
    .criteria-label, .criteria-modifier {
        line-height:40px;
        display:block;
    }
    .criteria-divider {
        margin:1em 0;
        text-align:left;
        text-transform: uppercase;
        color:#BDBDBD;
    }
</style>

<template>
    <div>
        <candy-attribute-translatable :languages="languages" :channels="getChannels(method.channels.data)"
            :attributes="group.attributes.data" :attributeData="method.attribute_data"
            :request="request">
        </candy-attribute-translatable>
    </div>
</template>
