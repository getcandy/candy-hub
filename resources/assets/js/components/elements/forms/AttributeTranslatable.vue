<script>
    export default {
        data() {
            return {
                translating: false,
                defaultLanguage: locale.current(),
                defaultChannel: 'ecommerce',
                translateLanguage: locale.current(),
                translateChannel: 'ecommerce',
                isDefault: true,
                originalData: []
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
            translateChannel: function(){
                this.isDefault = (this.defaultChannel === this.translateChannel && this.defaultLanguage === this.translateLanguage);
            },
            translateLanguage: function(){
                this.isDefault = (this.defaultChannel === this.translateChannel && this.defaultLanguage === this.translateLanguage);
            }
        },
        methods: {
            getError (mapping) {
                return this.request.getError(mapping);
            },
            hasError (mapping) {
                return this.request.hasError(mapping);
            },
            useDefault (obj) {
                if (obj.checked) {
                    this.set(obj.id, null);
                } else {
                    this.set(obj.id, this.get(obj.id, 'original'));
                }
            },
            get (handle, type) {
                var channel = '';
                var language = '';
                var source = {};

                if (type === 'default') {
                    channel = this.defaultChannel;
                    language = this.defaultLanguage;
                    source = this.attributeData;
                } else if (type === 'original') {
                    channel = this.translateChannel;
                    language = this.translateLanguage;
                    source = this.originalData;
                } else {
                    channel = this.translateChannel;
                    language = this.translateLanguage;
                    source = this.attributeData;
                }

                return _.get(source, handle+'.'+channel+'.'+language);
            },
            set(handle, value, type) {

                var channel = '';
                var language = '';

                if (type === 'default') {
                    channel = this.defaultChannel;
                    language = this.defaultLanguage;
                } else {
                    channel = this.translateChannel;
                    language = this.translateLanguage;
                }

                if (!this.attributeData[handle]) {
                    this.$set(this.attributeData, handle, {});
                }
                if (!this.attributeData[handle][channel]) {
                    this.$set(this.attributeData[handle], channel, {});
                }
                this.$set(this.attributeData[handle][channel], language, value);
            }
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
                                <button v-if="!translating" class="btn btn-default" @click="translating = true">Translate</button>
                                <button v-if="translating" class="btn btn-default" @click="translating = false">Hide Translation</button>
                            </div>

                            <div class="col-md-6">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Store Channels</label>
                                            <candy-select :options="channels" v-model="translateChannel" v-if="channels.length"></candy-select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Language</label>
                                            <candy-select :options="languages" v-model="translateLanguage" v-if="languages.length"></candy-select>
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

                            <!-- Label -->
                            <label :for="attribute.handle">{{ attribute.name }}</label>

                            <!-- Inputs -->
                            <candy-input v-if="attribute.type == 'text'"
                                        :handle="'default-'+ attribute.id"
                                        :value="get(attribute.handle, 'default')"
                                        @input="set(attribute.handle, $event, 'default')"
                                        :required="attribute.required">
                            </candy-input>
                            <candy-select v-if="attribute.type == 'select'"
                                        :id="'default-'+ attribute.id"
                                        v-model="attributeData[attribute.handle]"
                                        :options="attribute.lookups" :required="attribute.required">
                            </candy-select>
                            <candy-textarea v-if="attribute.type == 'textarea'"
                                            :id="'default-'+ attribute.id"
                                            :value="get(attribute.handle, 'default')"
                                            @input="set(attribute.handle, $event, 'default')"
                                            :required="attribute.required">
                            </candy-textarea>

                            <!-- Errors -->
                            <span class="text-danger" v-if="getError(attribute.handle)"
                                  v-text="getError(attribute.handle)">
                            </span>

                            <!--
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
-->
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-6" v-if="translating">
                        <div class="form-group" v-for="attribute in attributes">

                            <!-- Checkbox -->
                            <candy-checkbox v-show="attribute.translatable && !isDefault"
                                            :id="attribute.handle"
                                            @change="useDefault"
                                            :class="{ attributecheckbox: true }"
                                            :checked="(get(attribute.handle) === null)"
                                            :originalValue="get(attribute.handle)">
                                Use Default
                            </candy-checkbox>
                            <label v-show="isDefault">&nbsp;</label>

                            <!-- Inputs -->
                            <candy-input v-if="attribute.translatable && attribute.type === 'text'"
                                        :value="get(attribute.handle)"
                                        @input="set(attribute.handle, $event)"
                                        :required="attribute.required"
                                        :placeholder="(get(attribute.handle) === null ? get(attribute.handle, 'default') : '')"
                                        :disabled="(get(attribute.handle) === null || isDefault)">
                            </candy-input>
                            <candy-textarea v-if="attribute.translatable && attribute.type === 'textarea'"
                                            :value="get(attribute.handle)"
                                            @input="set(attribute.handle, $event)"
                                            :required="attribute.required"
                                            :placeholder="(get(attribute.handle) === null ? get(attribute.handle, 'default') : '')"
                                            :disabled="(get(attribute.handle) === null || isDefault)">
                            </candy-textarea>
                            <candy-select v-if="attribute.translatable && attribute.type === 'select'"
                                        :value="get(attribute.handle)"
                                        @input="set(attribute.handle, $event)"
                                        :required="attribute.required"
                                        :placeholder="(get(attribute.handle) === null ? get(attribute.handle, 'default') : '')"
                                        :disabled="(get(attribute.handle) === null || isDefault)"
                                        :options="attribute.lookups">
                            </candy-select>

                            <!-- Errors -->
                            <span class="text-danger"
                                  v-if="hasError(attribute.handle)"
                                  v-text="getError(attribute.handle)">
                            </span>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>