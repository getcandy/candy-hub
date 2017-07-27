<script>
    export default {
        data() {
            return {
                translating: false,
                languageOne: 'gb',
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
                    <div class="col-xs-12 form-group" :class="{ 'col-md-6': translating }">

                        <div class="form-group" v-for="input in group.attributes.data">

                            <label :for="input.handle">{{ input.name }}</label>

                            <div v-if="input.type == 'text'">

                                <candy-input v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-input>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'select'">

                                <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'textarea'">

                                <candy-textarea v-model="product.attributes[input.handle][channelOne][languageOne]" :required="input.required"></candy-textarea>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'date'">

                                <candy-date v-model="product.attributes[input.handle]" :required="input.required"></candy-date>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'time'">

                                <candy-time v-model="product.attributes[input.handle]" :required="input.required"></candy-time>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'radio'">

                                <candy-radio v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-radio>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'toggle'">

                                <candy-toggle v-model="product.attributes[input.handle]" :required="input.required"></candy-toggle>
                                <span class="text-danger" v-if="getError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>

                        </div>

                    </div>
                    <div  class="col-xs-12 col-md-6" v-if="translating">
                        <div class="form-group" v-for="input in group.attributes.data">

                            <label :for="input.handle">{{ input.name }}</label>

                            <div v-if="input.type == 'text'" :class="{'form-group': true}">

                                <candy-input v-if="translating" v-model="product.attributes[input.handle][channelTwo][languageTwo]" :value="getValue(input.handle)" :required="input.required"></candy-input>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelTwo, languageTwo))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>
                            <div v-else-if="input.type == 'select'" :class="{'form-group': true}">

                                <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>
                                <span class="text-danger" v-if="hasError(getValue(input.handle, channelOne, languageOne))" v-text="getError(getValue(input.handle, channelOne, languageOne))"></span>

                            </div>
                            <div v-else-if="input.type == 'textarea'" :class="{'form-group': true}">

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