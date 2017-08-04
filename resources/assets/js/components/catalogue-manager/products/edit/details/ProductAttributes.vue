<script>
    export default {
        data() {
            return {
                translating: false,
                defaultLanguage: 'gb',
                defaultChannel: 'ecommerce',
                languageTwo: 'se',
                channelTwo: 'ecommerce',
                channels: []
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
                return 'attributes.' + handle + '.' + channel + '.' + lang;
            },
            getError(mapping) {
                return this.request.getError(mapping);
            },
            hasError(mapping) {
                return this.request.hasError(mapping);
            },
            translate: function() {
                this.translating = !this.translating;
            },
            useDefault: function(obj) {

                let attr = obj.id.split('-');

                if(obj.checked) {
                    this.$set(this.product.attributes[attr[0]][attr[1]], attr[2], null);
                } else {
                    this.$set(this.product.attributes[attr[0]][attr[1]], attr[2], '');
                }

            }
        },
        mounted() {
            CandyEvent.$emit('current-tab', this);
            console.log(this.group.attributes.data);
            this.product.channels.data.forEach(channel => {
                this.channels.push({
                    label: channel.name,
                    value: channel.handle
                });
            });
        }
    }
</script>

<template>
    <div>
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">

                            <div class="col-md-6">

                                <button v-if="!translating" class="btn btn-default" @click="translate">Translate</button>
                                <button v-if="translating" class="btn btn-default" @click="translate">Hide Translation</button>

                            </div>

                            <div class="col-md-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Store Channels</label>
                                            <candy-select :options="channels" v-model="channelTwo" v-if="channels.length"></candy-select>
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

                                <candy-input
                                        :id="'default-'+ input.id"
                                        v-model="product.attributes[input.handle][defaultChannel][defaultLanguage]"
                                        :required="input.required">
                                </candy-input>
                                <span class="text-danger"
                                      v-if="hasError(getValue(input.handle, defaultChannel, defaultLanguage))"
                                      v-text="getError(getValue(input.handle, defaultChannel, defaultLanguage))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'select'">

                                <candy-select :id="'default-'+ input.id"
                                              v-model="product.attributes[input.handle]"
                                              :options="input.lookups" :required="input.required">
                                </candy-select>
                                <span class="text-danger"
                                      v-if="hasError(getValue(input.handle))"
                                      v-text="getError(getValue(input.handle))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'textarea'">

                                <candy-textarea :id="'default-'+ input.id"
                                                v-model="product.attributes[input.handle][defaultChannel][defaultLanguage]"
                                                :required="input.required">
                                </candy-textarea>
                                <span class="text-danger" v-if="getError(getValue(input.handle, defaultChannel, defaultLanguage))"
                                      v-text="getError(getValue(input.handle, defaultChannel, defaultLanguage))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'date'">

                                <candy-date :id="'default-'+ input.id"
                                            v-model="product.attributes[input.handle]"
                                            :required="input.required">
                                </candy-date>
                                <span class="text-danger"
                                      v-if="getError(getValue(input.handle))"
                                      v-text="getError(getValue(input.handle))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'time'">

                                <candy-time :id="'default-'+ input.id"
                                            v-model="product.attributes[input.handle]"
                                            :required="input.required">
                                </candy-time>
                                <span class="text-danger"
                                      v-if="getError(getValue(input.handle))"
                                      v-text="getError(getValue(input.handle))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'radio'">

                                <candy-radio :id="'default-'+ input.id"
                                             v-model="product.attributes[input.handle]"
                                             :options="input.lookups" :required="input.required">
                                </candy-radio>
                                <span class="text-danger"
                                      v-if="getError(getValue(input.handle))"
                                      v-text="getError(getValue(input.handle))">
                                </span>

                            </div>
                            <div v-else-if="input.type == 'toggle'">

                                <candy-toggle :id="'default-'+ input.id"
                                              v-model="product.attributes[input.handle]"
                                              :required="input.required">
                                </candy-toggle>
                                <span class="text-danger"
                                      v-if="getError(getValue(input.handle))"
                                      v-text="getError(getValue(input.handle))">
                                </span>

                            </div>

                        </div>

                    </div>
                    <div  class="col-xs-12 col-md-6" v-if="translating">
                        <div class="form-group" v-for="input in group.attributes.data">

                            <div v-if="input.scopeable && input.type === 'text'" :class="{'form-group': true}">

                                <candy-checkbox :id="input.handle +'-'+ channelTwo +'-'+ languageTwo"
                                                @change="useDefault"
                                                :class="{ attributecheckbox: true }"
                                                :checked="(product.attributes[input.handle][channelTwo][languageTwo] === null)"
                                                :originalValue="product.attributes[input.handle][channelTwo][languageTwo]">
                                    Use Default
                                </candy-checkbox>
                                <candy-input v-if="translating"
                                             v-model="product.attributes[input.handle][channelTwo][languageTwo]"
                                             :value="getValue(input.handle)"
                                             :required="input.required"
                                             :disabled="(product.attributes[input.handle][channelTwo][languageTwo] === null)">
                                </candy-input>
                                <span class="text-danger"
                                      v-if="hasError(getValue(input.handle, channelTwo, languageTwo))"
                                      v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>

                            <div v-else-if="input.scopeable && input.type === 'textarea'" :class="{'form-group': true}">

                                <candy-checkbox :id="input.handle +'-'+ channelTwo +'-'+ languageTwo"
                                                @change="useDefault"
                                                :class="{ attributecheckbox: true }"
                                                :checked="(product.attributes[input.handle][channelTwo][languageTwo] === null)"
                                                :originalValue="product.attributes[input.handle][channelTwo][languageTwo]">
                                    Use Default
                                </candy-checkbox>
                                <candy-textarea v-model="product.attributes[input.handle][channelTwo][languageTwo]"
                                                :required="input.required"
                                                :disabled="(product.attributes[input.handle][channelTwo][languageTwo] === null)">
                                </candy-textarea>

                                <span class="text-danger" v-if="getError(getValue(input.handle, channelTwo, languageTwo))" v-text="getError(getValue(input.handle, channelTwo, languageTwo))"></span>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>