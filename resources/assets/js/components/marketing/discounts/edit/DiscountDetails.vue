<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        data() {
            return {
                groups: [],
                customerGroups: [],
                languages: [],
                request: apiRequest
            }
        },
        props: {
            discount: {
                type: Object
            }
        },
        mounted() {
            this.loadLanguages();
        },
        methods: {
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
        components: {
            flatPickr
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
        <candy-tabs nested="true">
            <candy-tab v-for="(group, index) in discount.attribute_groups.data" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="collection-details">
                    <candy-attribute-translatable
                        :languages="languages"
                        :attributes="group.attributes.data"
                        :attributeData="discount.attribute_data"
                        :channels="getChannels(discount.channels.data)"
                        :request="request"
                    >
                    </candy-attribute-translatable>
                    <div class="form-group">
                        <label>Start date</label>
                        <flat-pickr class="datetimepicker" v-model="discount.start_at"></flat-pickr>
                    </div>
                    <div class="form-group">
                        <label>End date</label>
                        <flat-pickr class="datetimepicker" v-model="discount.end_at"></flat-pickr>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <input type="number" v-model="discount.priority" class="form-control">
                    </div>
            </candy-tab>
        </candy-tabs>
    </div>
</template>
