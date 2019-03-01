<script>

    import HasAttributes from '../../../mixins/HasAttributes.js';
    import FamilyDetails from './Edit/Details';
    import AttributeGroups from './Edit/AttributeGroups';

    export default {
        components: {
            FamilyDetails,
            AttributeGroups,
        },
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
                        CandyEvent.$emit('title-changed', {
                            title: this.family
                        });
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
            <candy-tabs initial="familyattributes">
                <candy-tab name="Details" :selected="true" dispatch="product-family-details">
                    <candy-tabs nested="true">
                        <candy-tab v-for="(group, index) in attribute_groups" :name="group.name" :handle="group.id" :key="group.id" :selected="index == 0 ? true : false" dispatch="product-details">
                            <family-details :family="family" :group="group"></family-details>
                        </candy-tab>
                    </candy-tabs>
                </candy-tab>
                <candy-tab name="Attribute Groups" dispatch="product-family-details">
                    <attribute-groups :groups="attribute_groups"></attribute-groups>
                </candy-tab>
            </candy-tabs>
        </template>
        <div v-else>
            <div class="page-loading loading">
                <span><i class="fa fa-sync fa-spin fa-3x fa-fw"></i></span> <strong>Loading</strong>
            </div>
        </div>
    </div>
</template>