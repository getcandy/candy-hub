<!--
  Product Details
  This component is responsible for saving product details
 -->
<template>
    <div class="row">
        <div class="col-xs-12 col-md-11">
            <div class="form-inline">
                <div class="form-group">
                    <label class="sr-only">Store Channels</label>
                    <select class="form-control selectpicker" v-model="channel">
                        <option value="0" data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                        <option value="1" data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                        <option value="2" data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only">Language</label>
                    <select class="form-control selectpicker" v-model="language">
                        <option value="en" data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                        <option value="sv" data-content="<span class='flag-icon flag-icon-sv'></span> Swedish">Swedish</option>
                    </select>
                </div>
                <button class="btn btn-default">Translate</button>
            </div>
            <hr>

            <div class="form-group" v-for="input in group.attributes.data">

                <div class="form-group">

                    <label :for="input.handle">{{ input.name }}</label>


                    <div id="original-data">
                        <div v-if="input.type == 'text'" class="form-group">

                            <candy-input v-model="attributes[input.handle][channel][defaultLanguage]" :required="input.required"></candy-input>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, defaultLanguage))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                        <div v-else-if="input.type == 'select'" class="form-group">

                            <candy-select v-model="attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, defaultLanguage))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                        <div v-else-if="input.type == 'textarea'" class="form-group">

                            <candy-textarea v-model="attributes[input.handle]" :required="input.required"></candy-textarea>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, defaultLanguage))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                    </div>

                    <div id="translated-data" v-if="defaultLanguage !== language">
                        <div v-if="input.type == 'text'" class="form-group">

                            <candy-input v-model="attributes[input.handle][channel][language]" :value="getValue(input.handle)" :required="input.required"></candy-input>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, language))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                        <div v-else-if="input.type == 'select'" class="form-group">

                            <candy-select v-model="attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, language))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                        <div v-else-if="input.type == 'textarea'" class="form-group">

                            <candy-textarea v-model="attributes[input.handle]" :required="input.required"></candy-textarea>
                            <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, language))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                update: apiRequest,
                translating: false,
                attributes: this.product.attribute_data,
                channel: 'ecommerce',
                defaultLanguage: 'en',
                language: 'en'
            }
        },
        props: {
            group: {
                type: Object
            },
            product: {
                type: Object
            }
        },
        methods: {
            save() {
                this.update.send('put', '/products/' + this.product.id, { 'attributes' : this.attributes })
                    .then(response => {
                        Event.$emit('notification', {
                            level: 'success'
                        });
                    })
                    .catch(errors => {
                        this.update.record(errors.response.data);
                    });

            },
            getValue(handle, channel, lang) {

                // need to tidy
                return 'attributes.'+ handle +'.'+ channel +'.'+ lang;

            }
        },
        mounted() {
            Event.$emit('current-tab', this);
        }
    }
</script>