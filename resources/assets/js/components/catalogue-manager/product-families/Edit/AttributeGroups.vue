<template>
    <div>
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
                                    <h4>Attribute Groups</h4>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-primary">
                                        <fa icon="plus"></fa> Add Attribute
                                    </button>
                                </div>
                            </div>
                            <hr>

                                <div v-for="group in groups" :key="group.id">
                                    <h4>{{ group.name|t }}</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="25%">Name</th>
                                                <th width="25%">Handle</th>
                                                <th width="25%">Type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="attribute in group.attributes.data" :key="attribute.id">
                                                <td>{{ attribute.name|t }}</td>
                                                <td><code>{{ attribute.handle }}</code></td>
                                                <td>{{ attribute.type }}</td>
                                                <td>
                                                    <a href="#">View</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    export default {
        props: {
            groups: {
                type: Array,
                default() {
                    return [];
                }
            }
        }
    }
</script>

<style scoped>

</style>