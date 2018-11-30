<script>
    import EditableTable from './../../elements/tables/Editable.vue';

    export default {
        data() {
            return {
                attribute : {},
                loaded: false,
                translating: false,
                groups: [],
                translationLanguage: locale.current(),
                languages: [],
                changeGroup: false,
                fields: {},
                params: {
                    includes: 'group'
                }
            }
        },
        props: ['id'],
        components: {
            'editable-table' : EditableTable
        },
        mounted() {
            this.loadLanguages();
            this.loadGroups();
            Dispatcher.add('save-attribute', this);
            this.load();
        },
        methods: {
            base() {
                return {
                    name: {
                        [locale.current()] : ''
                    },
                    handle: '',
                    type: 'text',
                    group_id: ''
                };
            },
            load() {
                apiRequest.send('get', '/attributes/' + this.id, [], this.params)
                    .then(response => {
                        this.attribute = response.data;
                        this.attribute.group_id = this.attribute.group.data.id;
                        this.fields['name'] = {
                            value: this.attribute.name,
                            type: 'text',
                            translatable: true
                        };
                        this.loaded = true;

                        // document.title = this.$options.filters.attribute(, 'name') + ' Category - GetCandy';
                    });
            },
            save() {
                apiRequest.send('put', '/attributes/' + this.id, this.attribute)
                    .then(response => {
                        CandyEvent.$emit('notification', {
                            level: 'success'
                        });
                        this.attribute = this.base();
                        CandyEvent.$emit('attribute-updated', response.data);
                    }).catch(response => {
                        console.log(response);
                        CandyEvent.$emit('notification', {
                            level: 'error',
                            message: 'Missing / Invalid fields'
                        });
                    });
            },
            loadGroups() {
                apiRequest.send('get', 'attribute-groups', [], []).then(response => {
                    this.groups = response.data;
                });
            },
            viewGroup(id) {
                return route('hub.attribute-groups.edit', id);
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

            <transition name="fade">
                <candy-tabs initial="attributedetails">
                    <candy-tab name="Attribute Details" handle="attribute-details" dispatch="save-attribute" :selected="true">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8">
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
                                            <input class="form-control" v-model="attribute.handle" :readonly="attribute.system">
                                        </div>
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select class="form-control" v-model="attribute.type" :disabled="attribute.system">
                                                <option value="text">Text</option>
                                                <option value="richtext">Richtext</option>
                                                <option value="select">Select</option>
                                                <option value="number">Number</option>
                                            </select>
                                        </div>
                                        <template v-if="attribute.type == 'select'">
                                            <div class="form-group">
                                                <label>Lookups</label>
                                                <editable-table></editable-table>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Settings</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><label for="searchable">Searchable</label></td>
                                                    <td><input type="checkbox" v-model="attribute.searchable" id="searchable" :disabled="attribute.system"></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="filterable">Filterable</label></td>
                                                    <td><input type="checkbox" v-model="attribute.filterable" id="filterable" :disabled="attribute.system"></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="required">Required</label></td>
                                                    <td><input type="checkbox" v-model="attribute.required" id="required" :disabled="attribute.system"></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="required">Translatable</label></td>
                                                    <td><input type="checkbox" v-model="attribute.translatable" id="required" :disabled="attribute.system"></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td><label for="channeled">Channeled</label></td>
                                                    <td><input type="checkbox" v-model="attribute.channeled" id="channeled"></td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                        <div class="form-group">
                                            <label>
                                                Attribute Group
                                            </label>
                                            <template v-if="!changeGroup">
                                                <br><strong>{{ attribute.group.data.name|t }} </strong>   &middot; <a href="" @click.prevent="changeGroup = true">change</a> / <a :href="viewGroup(attribute.group_id)">view</a>
                                            </template>
                                            <select class="form-control" v-model="attribute.group_id" v-if="changeGroup" @change="changeGroup = false">
                                                <option v-for="group in groups" :value="group.id">
                                                    {{ group.name['en'] }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </candy-tab>
                </candy-tabs>
            </transition>
        </template>

        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>

    </div>

</template>