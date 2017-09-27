<script>
    export default {
        data() {
            return {
                defaults: {
                    'language': 'en'
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
                type: Array,
                default: [
                    {'key':'', 'value':'', 'type':'', 'required':'', 'translatable':''}
                ]
            }
        },
        watch: {

        },
        methods: {
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            defaultLanguage() {
                return (this.params.language === this.defaults.language) ? true : false;
            },
            useDefault: function(obj) {


                let splitID = obj.id.split('-');
                let fieldKey = splitID[0];
                let fieldLang


                let oValue = '';

                console.log(field);

                if(obj.checked) {

                    this.fields[obj.id][params.language] = null;


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

            <div class="col-xs-12 form-group" :class="{ 'col-md-6': params.translating }">
                <div class="form-group" v-for="field in fields">

                    <label :for="field.key">{{ capitalize(field.key) }}</label>

                    <div v-if="field.type == 'text'">

                        <candy-input
                                :id="'default-'+ field.key"
                                v-model="field.value[defaults.language]"
                                :required="field.required">
                        </candy-input>

                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-md-6" v-if="params.translating">
                <div class="form-group" v-for="field in fields">

                    <div v-if="field.translatable && field.type === 'text'">
                        <candy-checkbox class="attributecheckbox"
                                        v-if="!defaultLanguage()"
                                        :id="field.key"
                                        @change="useDefault"
                                        :checked="(field.value[params.language] === null)">
                            Use Default
                        </candy-checkbox>
                        <label v-if="defaultLanguage()">&nbsp;</label>
                        <candy-input v-if="params.translating"
                                     v-model="field.value[params.language]"
                                     :required="field.required"
                                     :placeholder="(field.value[params.language] === null ? field.value[defaults.language] : '')"
                                     :disabled="(field.value[params.language] === null)">
                        </candy-input>

                    </div>

                </div>
            </div>

        </div>
    </div>
</template>