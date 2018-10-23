<script>

    import HasAttributes from '../../../mixins/HasAttributes.js';

    export default {
        mixins: [
            HasAttributes,
        ],
        data() {
            return {
                family : {},
                loaded: false,
                translating: false,
                languages: [],
                translationLanguage: locale.current(),
                fields: {},
                attribute_groups: [],
                params: {
                    includes: 'attribute_groups.attributes'
                }
            }
        },
        props: ['id'],
        mounted() {
            this.loadLanguages();
            this.load();
        },
        methods: {
            load() {
                apiRequest.send('get', '/product-families/' + this.id, [], this.params)
                    .then(response => {
                        this.family = response.data;

                        this.setUp(this.family.attribute_data);

                        this.attribute_groups = this.family.attribute_groups.data;

                        document.title = this.$options.filters.attribute(this.family, 'name') + ' Product Family - GetCandy';

                        this.loaded = true;
                    });
            },
            loadLanguages() {
                apiRequest.send('get', 'languages', [], []).then(response => {
                    response.data.forEach(lang => {
                        this.languages.push({
                            label: lang.name,
                            value: lang.lang,
                            content: '<span class=\'flag-icon flag-icon-' + lang.iso + '\'></span> ' + lang.name
                        });
                    });
                });
            }
        }
    }
</script>

<template>
    <div>
        <template v-if="loaded">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-xs-12 text-right">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <div v-show="translating">
                                            <label class="sr-only">Language</label>
                                            <candy-select :options="languages" v-model="translationLanguage" v-if="languages.length"></candy-select>
                                        </div>
                                    </div>
                                    <button v-if="!translating" class="btn btn-default" @click="translating = true">Translate</button>
                                    <button v-if="translating" class="btn btn-default" @click="translating = false">Hide Translation</button>
                                </div>

                            </div>
                            <candy-option-translatable :fields="fields"
                                :params="{'translating':translating, 'language':translationLanguage}">
                            </candy-option-translatable>

                            <div class="row">
                                <div class="col-md-8">
                                    <h4>Attributes</h4>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-primary">
                                        <fa icon="plus"></fa> Add Attribute
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="panel panel-default" v-for="group in attribute_groups">
                                <div class="panel-heading"><strong>{{ group.name|t }}</strong></div>
                                <ul class="list-group">
                                    <li v-for="attribute in group.attributes.data" class="list-group-item">
                                        {{ attribute.name|t }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>
    </div>
</template>