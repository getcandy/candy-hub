<!--
  Product Details
  This component is responsible for saving product details
 -->
<template>
    <div class="row">

        <div class="col-xs-6 col-md-6">
            <div class="form-inline">
                <div class="form-group">
                    <label class="sr-only">Store Channels</label>
                    <select class="form-control selectpicker" v-model="channelOne">
                        <option value="ecommerce" data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                        <option value="print" data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                        <option value="mobile" data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only">Language</label>
                    <select class="form-control selectpicker" v-model="languageOne" >
                        <option value="en" data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                        <option value="sv" data-content="<span class='flag-icon flag-icon-sv'></span> Swedish">Swedish</option>
                    </select>
                </div>
                <button class="btn btn-default" v-model="translating" value="true">Translate</button>
            </div>
            <hr>

            <div class="form-group" v-for="input in group.attributes.data">

                <label :for="input.handle">{{ input.name }}</label>

                <div v-if="input.type == 'text'" :class="{ 'translating': translating, 'form-group': true }">

                    <div class="original-lang">
                        <candy-input v-model="this.getModel('text', 'ecommerce', 'en')" :required="input.required"></candy-input>
                        <!--<candy-input v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-input>-->
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelOne, languageOne))" v-text="update.getError(getValue(input.handle, channel, languageOne))"></span>
                    </div>

                </div>

                <div v-else-if="input.type == 'select'" class="form-group" :class="{ 'translating': translating, 'form-group': true }">

                    <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>

                    <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelOne, languageOne))" v-text="update.getError(getValue(input.handle, channel, languageOne))"></span>

                </div>

                <div v-else-if="input.type == 'textarea'" class="form-group" :class="{ 'translating': translating, 'form-group': true }">
                    <div class="original-lang">
                        <candy-textarea v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-textarea>
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelOne, languageOne))" v-text="update.getError(getValue(input.handle, channel, languageOne))"></span>
                    </div>

                </div>

                <div v-else-if="input.type == 'radio'" class="form-group" :class="{ 'translating': translating, 'form-group': true }">

                    <candy-radio v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-radio>

                    <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelOne, languageOne))" v-text="update.getError(getValue(input.handle, channel, languageOne))"></span>

                </div>

            </div>

        </div>

        <div class="col-xs-6 col-md-6">
            <div class="form-inline">
                <div class="form-group">
                    <label class="sr-only">Store Channels</label>
                    <select class="form-control selectpicker" v-model="channelTwo">
                        <option value="ecommerce" data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                        <option value="print" data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                        <option value="mobile" data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only">Language</label>
                    <select class="form-control selectpicker" v-model="languageTwo">
                        <option value="en" data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                        <option value="sv" data-content="<span class='flag-icon flag-icon-sv'></span> Swedish">Swedish</option>
                    </select>
                </div>
            </div>
            <hr>

            <div class="form-group" v-for="input in group.attributes.data">

                <label :for="input.handle">{{ input.name }}</label>

<!--
                <div :class="{ 'translating': translating, 'form-group': true }">

                    <div class="original-lang">
                        <div v-component="'candy-'+ input.type" v-model="product.attributes[input.handle][channel][defaultLanguage]" :options="input.lookups" :required="input.required"></div>
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, defaultLanguage))" v-text="update.getError(getValue(input.handle, channel, defaultLanguage))"></span>
                    </div>

                    <div class="translated-lang">
                        <div v-component="'candy-'+ input.type" v-model="product.attributes[input.handle][channel][selectedLanguage]" :value="getValue(input.handle)" :options="input.lookups" :required="input.required"></div>
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channel, selectedLanguage))" v-text="update.getError(getValue(input.handle, channel, selectedLanguage))"></span>
                    </div>

                </div>
-->

                <div v-if="input.type == 'text'" class="form-group">

                    <div class="translated-lang">
                        <candy-input v-model="product.attributes[input.handle][channelTwo][languageTwo]" :value="getValue(input.handle)" :required="input.required"></candy-input>
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="update.getError(getValue(input.handle, channelTwo, languageTwo))"></span>
                    </div>

                </div>

                <div v-else-if="input.type == 'select'" class="form-group">

                    <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>

                    <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="update.getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                </div>

                <div v-else-if="input.type == 'textarea'" class="form-group">

                    <div class="translated-lang">
                        <candy-textarea v-model="product.attributes[input.handle][channelTwo][languageTwo]" :required="input.required"></candy-textarea>
                        <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="update.getError(getValue(input.handle, channelTwo, languageTwo))"></span>
                    </div>
                </div>

                <div v-else-if="input.type == 'radio'" class="form-group">

                    <candy-radio v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-radio>

                    <span class="text-danger" v-if="update.hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="update.getError(getValue(input.handle, channel, languageTwo))"></span>

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
                channelOne: 'ecommerce',
                channelTwo: 'ecommerce',
                languageOne: 'en',
                languageTwo: 'en'
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
        watch: {
            selectedLanguage(val) {
                //this.translating = this.defaultLanguage !== val;
            }
        },
        methods: {
            save() {
                this.update.send('put', '/products/' + this.product.id, { 'attributes' : this.product.attributes })
                    .then(response => {
                        Event.$emit('notification', {
                            level: 'success'
                        });
                    });
            },
            getValue(handle, channel, lang) {
                return 'attributes.'+ handle +'.'+ channel +'.'+ lang;
            },
            getModel(handle, channel, lang) {

                let preHandle = 'product.attributes.';

                if (typeof preHandle + handle + channel == 'undefined') {
                    return preHandle + handle;
                } else if (typeof preHandle + handle + channel + lang == 'undefined') {
                    return preHandle + handle + channel;
                }
                return preHandle + handle + channel + lang;
            }

        },
        mounted() {
            Event.$emit('current-tab', this);
        }
    }
</script>