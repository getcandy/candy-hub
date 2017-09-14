<script>
    export default {
        data() {
            return {
                translating: false,
                defaultLanguage: 'en',
                defaultChannel: 'ecommerce',
                languageTwo: 'se',
                channelTwo: 'ecommerce',
                isDefault: false
            }
        },
        props: {
            request: {
                type: Object
            },
            channels: {
                type: Array
            },
            languages: {
                type : Array
            },
            attributes: {
                type: Array
            },
            attributeData: {
                type: Object
            }
        },
        watch: {
            channelTwo: function(){
                this.isDefault = (this.defaultChannel === this.channelTwo && this.defaultLanguage === this.languageTwo);
            },
            languageTwo: function(){
                this.isDefault = (this.defaultChannel === this.channelTwo && this.defaultLanguage === this.languageTwo);
            }
        },
        methods: {
            getValue(handle, channel, lang) {
                return 'attributeData.' + handle + '.' + channel + '.' + lang;
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
                let oValue = '';

                if(obj.checked) {

                    this.originalData[attr[0]][attr[1]][attr[2]] = obj.originalValue;
                    this.$set(this.attributeData[attr[0]][attr[1]], attr[2], null);

                } else {

                    if(this.originalData[attr[0]][attr[1]][attr[2]]) {
                        oValue = this.originalData[attr[0]][attr[1]][attr[2]];
                        this.$set(this.attributeData[attr[0]][attr[1]], attr[2], this.originalData[attr[0]][attr[1]][attr[2]]);
                    }
                    this.$set(this.attributeData[attr[0]][attr[1]], attr[2], oValue);

                }
            }
        },
        mounted() {
            CandyEvent.$emit('current-tab', this);
        },
        created: function() {
            // Non Reactive Data
            this.originalData = JSON.parse(JSON.stringify(this.attributeData));
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

                        <div class="form-group" v-for="attribute in attributes">

                            <label :for="attribute.handle">{{ attribute.name }}</label>

                            <div v-if="attribute.type == 'text'">

                                <candy-input
                                        :id="'default-'+ attribute.id"
                                        v-model="attributeData[attribute.handle][defaultChannel][defaultLanguage]"
                                        :required="attribute.required">
                                </candy-input>
                                <span class="text-danger"
                                      v-if="hasError(getValue(attribute.handle, defaultChannel, defaultLanguage))"
                                      v-text="getError(getValue(attribute.handle, defaultChannel, defaultLanguage))">
                                </span>

                            </div>
                            <div v-else-if="attribute.type == 'select'">

                                <candy-select :id="'default-'+ attribute.id"
                                              v-model="attributeData[attribute.handle]"
                                              :options="attribute.lookups" :required="attribute.required">
                                </candy-select>
                                <span class="text-danger"
                                      v-if="hasError(getValue(attribute.handle))"
                                      v-text="getError(getValue(attribute.handle))">
                                </span>

                            </div>
                            <div v-else-if="attribute.type == 'textarea'">

                                <candy-textarea :id="'default-'+ attribute.id"
                                                v-model="attributeData[attribute.handle][defaultChannel][defaultLanguage]"
                                                :required="attribute.required">
                                </candy-textarea>
                                <span class="text-danger" v-if="getError(getValue(attribute.handle, defaultChannel, defaultLanguage))"
                                      v-text="getError(getValue(attribute.handle, defaultChannel, defaultLanguage))">
                                </span>

                            </div>
                            <div v-else-if="attribute.type == 'date'">



                            </div>
                            <div v-else-if="attribute.type == 'time'">



                            </div>
                            <div v-else-if="attribute.type == 'radio'">

                                <candy-radio :id="'default-'+ attribute.id"
                                             v-model="attributeData[attribute.handle]"
                                             :options="attribute.lookups" :required="attribute.required">
                                </candy-radio>
                                <span class="text-danger"
                                      v-if="getError(getValue(attribute.handle))"
                                      v-text="getError(getValue(attribute.handle))">
                                </span>

                            </div>
                            <div v-else-if="attribute.type == 'toggle'">

                                <candy-toggle :id="'default-'+ attribute.id"
                                              v-model="attributeData[attribute.handle]"
                                              :required="attribute.required">
                                </candy-toggle>
                                <span class="text-danger"
                                      v-if="getError(getValue(attribute.handle))"
                                      v-text="getError(getValue(attribute.handle))">
                                </span>

                            </div>

                        </div>

                    </div>
                    <div class="col-xs-12 col-md-6" v-if="translating">
                        <div class="form-group" v-for="attribute in attributes">

                            <div v-if="attribute.scopeable && attribute.type === 'text'" :class="{'form-group': true}">

                                <candy-checkbox v-if="!isDefault"
                                                :id="attribute.handle +'-'+ channelTwo +'-'+ languageTwo"
                                                @change="useDefault"
                                                :class="{ attributecheckbox: true }"
                                                :checked="(attributeData[attribute.handle][channelTwo][languageTwo] === null)"
                                                :originalValue="attributeData[attribute.handle][channelTwo][languageTwo]">
                                    Use Default
                                </candy-checkbox>
                                <label v-if="isDefault">&nbsp;</label>
                                <candy-input v-if="translating"
                                             v-model="attributeData[attribute.handle][channelTwo][languageTwo]"
                                             :value="getValue(attribute.handle)"
                                             :required="attribute.required"
                                             :placeholder="(attributeData[attribute.handle][channelTwo][languageTwo] === null ? attributeData[attribute.handle][defaultChannel][defaultLanguage] : '')"
                                             :disabled="(attributeData[attribute.handle][channelTwo][languageTwo] === null)">s
                                </candy-input>
                                <span class="text-danger"
                                      v-if="hasError(getValue(attribute.handle, channelTwo, languageTwo))"
                                      v-text="getError(getValue(attribute.handle, channelTwo, languageTwo))"></span>

                            </div>

                            <div v-else-if="attribute.scopeable && attribute.type === 'textarea'" :class="{'form-group': true}">

                                <candy-checkbox v-if="!isDefault"
                                                :id="attribute.handle +'-'+ channelTwo +'-'+ languageTwo"
                                                @change="useDefault"
                                                :class="{ attributecheckbox: true }"
                                                :checked="(attributeData[attribute.handle][channelTwo][languageTwo] === null)"
                                                :originalValue="attributeData[attribute.handle][channelTwo][languageTwo]">
                                    Use Default
                                </candy-checkbox>
                                <label v-if="isDefault">&nbsp;</label>
                                <candy-textarea v-model="attributeData[attribute.handle][channelTwo][languageTwo]"
                                                :required="attribute.required"
                                                :placeholder="(attributeData[attribute.handle][channelTwo][languageTwo] === null ? attributeData[attribute.handle][defaultChannel][defaultLanguage] : '')"
                                                :disabled="(attributeData[attribute.handle][channelTwo][languageTwo] === null)">
                                </candy-textarea>

                                <span class="text-danger" v-if="getError(getValue(attribute.handle, channelTwo, languageTwo))" v-text="getError(getValue(attribute.handle, channelTwo, languageTwo))"></span>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>