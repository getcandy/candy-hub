<script>

    export default {
        data() {
            return {
                attribute : {},
                loaded: false,
                translating: false,
                attributes: [],
                groups: [],
                translationLanguage: locale.current(),
                languages: [],
                fields: {},
                sortableOptions: {
                    onEnd: this.reorder,
                    filter: '.disabled',
                    handle: '.handle',
                    animation: 150
                },
                params: {
                    includes: 'attributes'
                }
            }
        },
        props: ['id'],
        mounted() {
            this.loadLanguages();
            this.load();
        },
        computed: {
            sortedAttributes() {
                return _.orderBy(this.attributes, 'position');
            }
        },
        methods: {
            load() {
                apiRequest.send('get', '/attribute-groups/' + this.id, [], this.params)
                    .then(response => {
                        this.group = response.data;
                        this.fields['name'] = {
                            value: this.group.name,
                            type: 'text',
                            translatable: true
                        };
                        this.attributes = this.group.attributes.data;
                        this.loaded = true;
                    });
            },
            refresh(attributes) {
                if (attributes) {
                    this.attributes = attributes;
                }
                let pos = 1;
                this.attributes.forEach(attribute => {
                    attribute.position = pos;
                    pos++;
                });
            },
            editAttribute(id) {
                return route('hub.attributes.edit', id);
            },
            reorder ({oldIndex, newIndex}) {
                const movedItem = this.attributes.splice(oldIndex, 1)[0];
                this.attributes.splice(newIndex, 0, movedItem);
                this.refresh();
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
                                        <template v-if="attribute.system">
                                            <div class="alert alert-warning">
                                                This is a system required attribute so editing may be limited on fields.
                                            </div>
                                        </template>
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
                                        <div class="form-group">
                                            <label>Handle</label>
                                            <input class="form-control" v-model="group.handle">
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>Attributes</h4>
                                            </div>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Handle</th>
                                                    <th>Type</th>
                                                    <th>Searchable</th>
                                                </tr>
                                            </thead>
                                            <tbody v-sortable="sortableOptions">
                                                <tr v-for="attribute in sortedAttributes" :key="attribute.id">
                                                    <td class="handle">
                                                        <svg width="13px" viewBox="0 0 13 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <g id="Artboard" fill="#D8D8D8">
                                                                    <rect id="Rectangle" x="2" y="2" width="3" height="3"></rect>
                                                                    <rect id="Rectangle-Copy-2" x="2" y="8" width="3" height="3"></rect>
                                                                    <rect id="Rectangle-Copy-4" x="2" y="14" width="3" height="3"></rect>
                                                                    <rect id="Rectangle-Copy-5" x="8" y="14" width="3" height="3"></rect>
                                                                    <rect id="Rectangle-Copy" x="8" y="2" width="3" height="3"></rect>
                                                                    <rect id="Rectangle-Copy-3" x="8" y="8" width="3" height="3"></rect>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </td>
                                                    <td>
                                                        <a :href="editAttribute(attribute.id)">
                                                            {{ attribute.name|t }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <code>{{ attribute.handle }}</code>
                                                    </td>
                                                    <td>{{ attribute.type }}</td>
                                                    <td>
                                                        <input type="checkbox" value="1" v-model="attribute.searchable" :disabled="attribute.system">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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