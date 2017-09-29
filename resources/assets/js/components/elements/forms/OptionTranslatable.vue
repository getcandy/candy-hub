<script>
    export default {
        data() {
            return {
                defaults: {
                    'language': locale.current()
                },
                originalFields: []
            }
        },
        props: {
            params: {
                type: Object,
                default: {'translating':false, 'language':'en'}
            },
            fields: {
                type: Object,
                default: {}
            }
        },
        methods: {
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            isDefault() {
                return (this.params.language === this.defaults.language) ? true : false;
            },
            useDefault: function(obj) {
                if(obj.checked) {
                    this.fields[obj.id].value[this.params.language] = null;
                } else {
                    this.fields[obj.id].value[this.params.language] = this.originalFields[obj.id].value[this.params.language];
                }
            }
        },
        created: function() {
            // Non Reactive Data
            this.originalFields = JSON.parse(JSON.stringify(this.fields));
        }
    }
</script>

<template>
    <div>
        <div class="row">

            <!-- Default -->
            <div class="col-xs-12 form-group" :class="{ 'col-md-6': params.translating }">
                <div class="form-group" v-for="(field, key) in fields">

                    <label :for="key">{{ capitalize(key) }}</label>

                    <div v-if="field.type == 'text'">
                        <candy-input
                                :id="'default-'+ key"
                                v-model="field.value[defaults.language]">
                        </candy-input>
                    </div>
                    <div v-if="field.type == 'textarea'">
                        <candy-textarea :id="'default-'+ key"
                                        v-model="field.value[defaults.language]">
                        </candy-textarea>
                    </div>
                    <div v-if="field.type == 'select'">
                        <candy-select :id="'default-'+ key"
                                      v-model="field.value[defaults.language]"
                                      :options="field.lookups">
                        </candy-select>
                    </div>

                </div>
            </div>

            <!-- Translation -->
            <div class="col-xs-12 col-md-6" v-if="params.translating">
                <div class="form-group" v-for="(field, key) in fields">

                    <div v-if="field.translatable && field.type === 'text'">
                        <candy-checkbox class="attributecheckbox"
                                        v-if="!isDefault()"
                                        :id="key"
                                        @change="useDefault"
                                        :checked="(field.value[params.language] === null)">
                            Use Default
                        </candy-checkbox>
                        <label v-if="isDefault()">&nbsp;</label>
                        <candy-input v-if="params.translating"
                                     v-model="field.value[params.language]"
                                     :placeholder="(field.value[params.language] === null ? field.value[defaults.language] : '')"
                                     :disabled="(field.value[params.language] === null)">
                        </candy-input>

                    </div>

                </div>
            </div>

        </div>
    </div>
</template>