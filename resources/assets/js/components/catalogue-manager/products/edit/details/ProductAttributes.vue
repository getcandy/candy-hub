<script>
    export default {
        data() {
            return {
              translating: false,
              channel: 'ecommerce',
              defaultLanguage: 'en',// need to get this dynamically
              selectedLanguage: 'en'
            }
        },
        props: {
            request: {
              type: Object
            },
            group: {
                type: Object
            },
            product: {
                type: Object
            }
        },
        watch: {
            selectedLanguage(val) {
                this.translating = this.defaultLanguage !== val;
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
            }
        }
    }
</script>

<template>
    <div class="row">
        <div class="col-xs-12 col-md-11">
            <div class="form-inline">
                <div class="form-group">
                    <label class="sr-only">Store Channels</label>
                    <select class="form-control selectpicker" v-model="channel">
                        <option value="ecommerce" data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                        <option value="print" data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                        <option value="mobile" data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="sr-only">Language</label>
                    <select class="form-control selectpicker" v-model="selectedLanguage" >
                        <option value="en" data-content="<span class='flag-icon flag-icon-gb'></span> English" selected>English</option>
                        <option value="sv" data-content="<span class='flag-icon flag-icon-sv'></span> Swedish">Swedish</option>
                    </select>
                </div>
                <button class="btn btn-default">Translate</button>
            </div>
            <hr>

            <div class="form-group" v-for="input in group.attributes.data">

                <label :for="input.handle">{{ input.name }}</label>

                <div v-if="input.type == 'text'" :class="{ 'translating': translating, 'form-group': true }">

                    <div class="original-lang">
                        <candy-input v-model="product.attributes[input.handle][channel][defaultLanguage]" :required="input.required"></candy-input>
                        <span class="text-danger" v-if="hasError(getValue(input.handle, channel, defaultLanguage))" v-text="getError(getValue(input.handle, channel, defaultLanguage))"></span>
                    </div>

                    <div class="translated-lang">
                        <candy-input v-if="translating" v-model="product.attributes[input.handle][channel][selectedLanguage]" :value="getValue(input.handle)" :required="input.required"></candy-input>
                        <span class="text-danger" v-if="hasError(getValue(input.handle, channel, selectedLanguage))" v-text="getError(getValue(input.handle, channel, selectedLanguage))"></span>
                    </div>

                </div>
                <div v-else-if="input.type == 'select'" class="form-group" :class="{ 'translating': translating, 'form-group': true }">

                    <candy-select v-model="product.attributes[input.handle]" :options="input.lookups" :required="input.required"></candy-select>

                    <span class="text-danger" v-if="hasError(getValue(input.handle, channel, defaultLanguage))" v-text="getError(getValue(input.handle, channel, defaultLanguage))"></span>

                </div>
                <div v-else-if="input.type == 'textarea'" class="form-group" :class="{ 'translating': translating, 'form-group': true }">
                    <div class="original-lang">
                        <candy-textarea v-model="product.attributes[input.handle]" :required="input.required"></candy-textarea>
                        <candy-textarea v-model="product.attributes[input.handle]" :required="input.required"></candy-textarea>
                    </div>

                    <div class="translated-lang">
                        <span class="text-danger" v-if="getError(getValue(input.handle, channel, defaultLanguage))" v-text="getError(getValue(input.handle, channel, defaultLanguage))"></span>
                        <span class="text-danger" v-if="hasError(getValue(input.handle, channel, language))" v-text="getError(getValue(input.handle, channel, language))"></span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>