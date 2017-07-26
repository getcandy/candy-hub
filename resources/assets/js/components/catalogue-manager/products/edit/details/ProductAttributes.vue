<script>
    export default {
        data() {
            return {
                translating: false,
                languageOne: 'en',
                channelOne: 'ecommerce',
                languageTwo: 'sv',
                channelTwo: 'ecommerce',
                channels: [{label: 'Storefront', value: 'ecommerce', content: '<i class=\'fa fa-shopping-cart\'></i> Storefront'},
                    {label: 'eBay', value: 'print', content: '<i class=\'fa fa-shopping-bag\'></i> eBay'},
                    {label: 'Facebook', value: 'mobile', content: '<i class=\'fa fa-facebook\'></i> Facebook'}]
            }
        },
        props: {
            request: {
                type: Object
            },
            languages: {
                type : Array
            },
            group: {
                type: Object
            },
            product: {
                type: Object
            }
        },
        methods: {
            getValue(handle, channel, lang) {
                return 'attributes.'+ handle +'.'+ channel +'.'+ lang;
            },
            getError(mapping) {
                return this.request.getError(mapping);
            },
            hasError(mapping) {
                return this.request.hasError(mapping);
            },
            translate: function() {
                this.translating = !this.translating;
            }
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
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-11">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label class="sr-only">Store Channels</label>
                                        <candy-select :options="channels" v-model="channelOne"></candy-select>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Language</label>
                                        <candy-select :options="languages" v-model="languageOne"></candy-select>
                                    </div>
                                    <button class="btn btn-default" @click="translate">{{ translating ? 'Translating..' : 'Translate' }}</button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Store Channels</label>
                                            <candy-select :options="channels" v-model="channelTwo"></candy-select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Language</label>
                                            <candy-select :options="languages" v-model="languageTwo"></candy-select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12" :class="{ 'col-md-6': translating, 'form-group': true }">

                        <div class="form-group" v-for="input in group.attributes.data">

                            <label :for="input.handle">{{ input.name }}</label>

                            <div v-if="input.type == 'text'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-input v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-input>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'select'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'textarea'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-textarea v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-textarea>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>

                        </div>

                    </div>
                    <div  class="col-xs-12 col-md-6" v-if="translating">
                        <div class="form-group" v-for="input in group.attributes.data">

                            <label :for="input.handle">{{ input.name }}</label>

                            <div v-if="input.type == 'text'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-input v-if="translating" v-model="product.attributes[input.handle][channelTwo][languageTwo]" :value="getValue(input.handle)" :required="input.required"></candy-input>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'select'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'textarea'" :class="{ 'translating': translating, 'form-group': true }">

                                <candy-textarea v-model="product.attributes[input.handle][channelTwo][languageTwo]" :required="input.required"></candy-textarea>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelTwo, languageTwo))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>